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

    $profilePicturePath = null;

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
                $profilePicturePath = 'assets/uploads/pfps/' . $newFilename;
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
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($error)) echo '<p style="color:red;">' . $error . '</p>'; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>"><br><br>

        <label>Name:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>"><br><br>

        <label>Surname:</label><br>
        <input type="text" name="surname" value="<?= htmlspecialchars($user['surname']) ?>"><br><br>

        <label>Location:</label><br>
        <input type="text" name="location" value="<?= htmlspecialchars($user['location']) ?>"><br><br>

        <label>Phone Number:</label><br>
        <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>"><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br><br>

        <label>Bio:</label><br>
        <textarea name="bio" rows="4" value="<?= htmlspecialchars($userData['bio'] ?? '') ?>"></textarea><br><br>

        <label>Profile Picture:</label><br>
        <input type="file" name="profile_picture"><br><br>

        <?php if (!empty($user['profile_picture'])): ?>
            <img src="<?= htmlspecialchars('../' . $user['profile_picture']) ?>" alt="Current Profile Picture" style="max-width: 150px;"><br><br>
        <?php endif; ?>

        <button type="submit">Update Profile</button>
    </form>
</body>
</html>
