<?php
// test_notifications.php

require_once __DIR__ . '/Notification.php';
require_once __DIR__ . '/../../Config/Database.php'; // adjust path as needed

use App\Models\Notifications;

echo "<h1>Testing getUserProfilePicture method in Notifications</h1>";

$notifications = new Notifications();

$testUserId = 46; // replace with a real user ID from your DB

echo "<h2>Testing getUserProfilePicture({$testUserId})</h2>";
$profilePic = $notifications->getUserProfilePicture($testUserId);

if ($profilePic !== null) {
    echo "<p style='color:green;'>✅ Profile picture path: {$profilePic}</p>";
    echo "<img src='{$profilePic}' alt='Profile Picture' style='max-width:200px;' />";
} else {
    echo "<p style='color:red;'>❌ No profile picture found or an error occurred.</p>";
}
?>
