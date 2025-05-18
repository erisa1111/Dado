<?php
// Include your Database class and JobModel class files manually

require_once __DIR__ . '/JobModel.php';
require_once __DIR__ . '/../../Config/Database.php'; 

use App\Models\JobModel;

$jobModel = new JobModel();

// Replace with a valid user ID
$userId = 46;

$jobs = $jobModel->getJobsforUser($userId);

echo "<pre>";
print_r($jobs);
echo "</pre>";
