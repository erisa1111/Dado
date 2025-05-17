<?php
session_start();

use App\Models\User;

require_once __DIR__ . '/../../App/Models/User.php';

if (!isset($_SESSION['user_id'])) {
    echo "No user logged in!";
    exit();
}

$loggedInUserId = $_SESSION['user_id'];

// Retrieve search parameters
$usernameSearch = isset($_GET['username']) ? trim($_GET['username']) : '';
$location = $_GET['location'] ?? null;
$roleId = $_GET['role_id'] ?? null;
$minRating = $_GET['min_rating'] ?? null;

if ($usernameSearch === '') {
    echo "Please enter a search term.";
    exit();
}

$userModel = new User();
$matchingUsers = $userModel->searchUsersWithFilters($usernameSearch, $location, $roleId, $minRating);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="/assets/css/search_results.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <div id="nav-placeholder"></div>
    </header>
    <br><br><br><br>

    <div class="results-container">
        <h2>Search Results for "<?= htmlspecialchars($usernameSearch) ?>"</h2>

        <!-- Filter Form -->
        <form method="GET" action="search_results.php" class="filter-form">
            <input type="text" name="username" placeholder="Search username..." value="<?= htmlspecialchars($usernameSearch) ?>">

            <select name="location">
                <option value="">All Locations</option>
                <option value="New York" <?= $location === 'New York' ? 'selected' : '' ?>>New York</option>
                <option value="London" <?= $location === 'London' ? 'selected' : '' ?>>London</option>
                <!-- Add more cities as needed -->
            </select>

           <select name="role_id">
                <option value="">All Roles</option>
                <option value="1" <?= $roleId === '1' ? 'selected' : '' ?>>Admin</option>
                <option value="2" <?= $roleId === '2' ? 'selected' : '' ?>>Nanny</option>
                <option value="0" <?= $roleId === '0' ? 'selected' : '' ?>>Parent</option>
            </select>


            <select name="min_rating">
                <option value="">Any Rating</option>
                <option value="5" <?= $minRating === '5' ? 'selected' : '' ?>>5 stars</option>
                <option value="4" <?= $minRating === '4' ? 'selected' : '' ?>>4+ stars</option>
                <option value="3" <?= $minRating === '3' ? 'selected' : '' ?>>3+ stars</option>
                <!-- etc. -->
            </select>

            <button type="submit">Filter</button>
        </form>

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
                            <a href="profile.php?user_id=<?= $user['id'] ?>">
                                <p class="username"><?= htmlspecialchars($user['username']) ?></p>
                                <p class="full_name"><?= htmlspecialchars($user['name']) . ' ' . htmlspecialchars($user['surname']) ?></p>
                            </a>
                            <p class="role"><?= htmlspecialchars($user['role_name']) ?></p>
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
