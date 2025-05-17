<?php
require_once __DIR__ . '/JobPost.php'; // adjust path as needed
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\JobPost;

echo "<h1>Testing JobPost model</h1>";

$jobModel = new JobPost();

// Replace with actual IDs from your DB
$nannyId = 86;
$jobPostId = 1;

echo "<h2>Testing hasAlreadyApplied(nannyId: $nannyId, jobPostId: $jobPostId)</h2>";
$result = $jobModel->hasAlreadyApplied($nannyId, $jobPostId);
echo "<pre>";
echo $result ? '✅ Already applied' : '❌ Not applied';
echo "</pre>";