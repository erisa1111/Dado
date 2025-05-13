<?php
session_start();
require_once __DIR__ . '/../../App/Models/User.php';

use App\Models\User;

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userModel = new User();
$userId = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $name = $_POST['name'] ?? '';
    $surname = $_POST['surname'] ?? '';
    $location = $_POST['location'] ?? '';
    $phone = $_POST['phone_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $bio = $_POST['bio'] ?? '';

    $profilePicturePath = $_POST['current_profile_picture'] ?? null; // Keep current profile picture if no new one is uploaded

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../assets/uploads/pfps';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // Create directory if not exists
        }

        $filename = basename($_FILES['profile_picture']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowedExtensions)) {
            $newFilename = uniqid('pfp_') . '.' . $ext;
            $targetPath = $uploadDir . '/' . $newFilename;

            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                $profilePicturePath = 'assets/uploads/pfps/' . $newFilename; // Update profile picture path
            }
        } else {
            echo "<p style='color:red;'>Invalid image format. Only JPG, PNG, GIF allowed.</p>";
        }
    }

    try {
        $userModel->updateProfile($userId, [
            'username' => $username,
            'name' => $name,
            'surname' => $surname,
            'location' => $location,
            'phone_number' => $phone,
            'email' => $email,
            'bio' => $bio,
            'profile_picture' => $profilePicturePath
        ]);

        $_SESSION['success'] = 'Profile updated successfully!';
        header('Location: profile.php');
        exit();
    } catch (Exception $e) {
        $error = 'Failed to update profile: ' . $e->getMessage();
    }
}

// Get current user data for form pre-fill
$user = $userModel->getProfile($userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="path_to_your_styles.css"> <!-- External CSS file -->
    <style>
        /* Same styling as before */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: inline-block;
        }

        .form-container textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-container button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        .form-container button:hover {
            background-color: #45a049;
        }

        .profile_image {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile_image img {
            max-width: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .form-container input[type="file"] {
            padding: 6px;
            border: 1px solid #ccc;
        }

        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($error)) echo '<p class="error-message">' . $error . '</p>'; ?>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="profile_image">
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="<?= htmlspecialchars('../' . $user['profile_picture']) ?>" alt="Current Profile Picture">
                <?php endif; ?>
                <p><strong>Current Profile Picture</strong></p>
            </div>

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required>

            <label for="surname">Surname:</label>
            <input type="text" name="surname" id="surname" value="<?= htmlspecialchars($user['surname']) ?>" required>

            <label for="location">Location:</label>
            <input type="text" name="location" id="location" value="<?= htmlspecialchars($user['location']) ?>" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="bio">Bio:</label>
            <textarea name="bio" id="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

            <label for="profile_picture">Profile Picture:</label>
            <input type="file" name="profile_picture" id="profile_picture">

            <!-- Hidden field to preserve the current profile picture -->
            <input type="hidden" name="current_profile_picture" value="<?= htmlspecialchars($user['profile_picture'] ?? '') ?>">

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
