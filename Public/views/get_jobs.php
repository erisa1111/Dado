<?php
session_start();

// routes/job.php
require_once __DIR__ . '/../../App/Models/JobModel.php';
require_once __DIR__ . '/../../App/Controllers/JobController.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\JobController;


$jobController = new JobController();


if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $jobController->getJobsByUser($userId);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'User not logged in.']);
}

?>