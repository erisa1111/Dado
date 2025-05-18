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

$testApplicationId = 21;

echo "<h2>Testing createJobFromApplication({$testApplicationId})</h2>";
$jobCreated = $notifications->createJobFromApplication($testApplicationId);

if ($jobCreated) {
    echo "<p style='color:green;'>✅ Job was successfully created from application ID {$testApplicationId}.</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to create job from application ID {$testApplicationId}. Check error log.</p>";
}

$testNannyId = 94; // replace with a real nanny_id from your DB
$testJobPostId = 3; // replace with a job_post_id they applied to

echo "<h2>Testing getAcceptedApplicationNotification(nannyId: {$testNannyId}, jobPostId: {$testJobPostId})</h2>";
$acceptedNotification = $notifications->getAcceptedApplicationNotification($testNannyId, $testJobPostId);
echo "<pre>";
print_r($acceptedNotification);
echo "</pre>";

?>