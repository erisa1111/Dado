<?php
session_start();

use App\Models\User;

require_once __DIR__ . '/../../App/Models/User.php';

if (!isset($_SESSION['user_id'])) {
    echo "No user logged in!";
    exit();
}

$loggedInUserId = $_SESSION['user_id'];
$usernameSearch = isset($_GET['username']) ? trim($_GET['username']) : '';

if ($usernameSearch === '') {
    echo "Please enter a search term.";
    exit();
}

$userModel = new User();
$matchingUsers = $userModel->searchUsersByUsername($usernameSearch);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/search_results.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>Search Results</title>
</head>
<body>
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br><br>

     <div class="results-container">
        <h2>Search Results for "<?php echo htmlspecialchars($usernameSearch); ?>"</h2>

        <?php if (!empty($matchingUsers)): ?>
            <ul class="user-list">
                <?php foreach ($matchingUsers as $user): ?>
                    <li class="user-card">
                        <?php
                            $profilePic = !empty($user['profile_picture']) 
                                ? '/' . htmlspecialchars($user['profile_picture']) 
                                : '/assets/img/default_profile.webp';
                        ?>
                        <div class="user-avatar">
                            <img src="<?= $profilePic ?>" alt="Profile Picture">
                        </div>
                        <div class="user-info">
                            <a href="profile.php?user_id=<?php echo $user['id']; ?>">
                                <p class="username"> <?php echo htmlspecialchars($user['username']); ?> </p>
                                <p class="full_name"><?= htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']) ?></p>
                            </a>
                            <p class="role"><?= htmlspecialchars($user['role_name']) ?> </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-results">No users found.</p>
        <?php endif; ?>
    </div>

    <script src="../components/nav_home/nav_home.js"></script>

    
</body>
</html>