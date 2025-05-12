<?php

require_once __DIR__ . '/../../../App/Models/User.php';
require_once __DIR__ . '/../../../Config/Database.php';

use App\Models\User;

if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    return;
}

$userModel = new User();
$userData = $userModel->getProfile($_SESSION['user_id']);

if (!$userData) {
    echo "User data not found.";
    return;
}

$profilePic = !empty($userData['profile_picture']) 
    ? '/' . htmlspecialchars($userData['profile_picture']) 
    : '/assets/img/default_profile.webp';
?>

<div class="profile">
    <div class="photo">
        <img src="<?= $profilePic ?>" alt="Profile Picture" class="profile-image" id="profile-image">
        <div class="info">
            <h3 class="name"><?= htmlspecialchars($userData['name']) . ' ' . htmlspecialchars($userData['surname']) ?></h3>
            <p class="status"><?= htmlspecialchars($userData['username']) ?></p>
        </div>
    </div>
    <div class="bio">
        <div class="bio-box">
            <p><?= htmlspecialchars($userData['bio']) ?></p>
        </div>
    </div>
</div>
