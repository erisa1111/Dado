<?php
// test_notifications.php

require_once __DIR__ . '/Notification.php';
require_once __DIR__ . '/../../Config/Database.php'; // adjust path as needed

use App\Models\Notifications;

echo "<h1>Testing Notifications model</h1>";

$notifications = new Notifications();

// replace with a real user ID who owns some posts in your DB
$userId = 46;

echo "<h2>getCommentsNotifications({$userId})</h2>";
$comments = $notifications->getCommentsNotifications($userId);
echo "<pre>";
print_r($comments);
echo "</pre>";

echo "<h2>getLikesNotifications({$userId})</h2>";
$likes = $notifications->getLikesNotifications($userId);
echo "<pre>";
print_r($likes);
echo "</pre>";

echo "<h2>getAllNotifications({$userId})</h2>";
$all = $notifications->getAllNotifications($userId);
echo "<pre>";
print_r($all);
echo "</pre>";

echo "<h2>getJobPostCommentsNotifications({$userId})</h2>";
$jobComments = $notifications->getJobPostCommentsNotifications($userId);
echo "<pre>";
print_r($jobComments);
echo "</pre>";

echo "<h2>getJobPostLikesNotifications({$userId})</h2>";
$jobLikes = $notifications->getJobPostLikesNotifications($userId);
echo "<pre>";
print_r($jobLikes);
echo "</pre>";

echo "<h2>getJobPostApplicationsNotifications({$userId})</h2>";
$applications = $notifications->getJobPostApplicationsNotifications($userId);
echo "<pre>";
print_r($applications);
echo "</pre>";
