<?php
require_once __DIR__ . '/../../App/Models/JobPost.php';
require_once __DIR__ . '/../../App/Controllers/JobPostController.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\JobPostController;

ini_set('display_errors', 0);
error_reporting(E_ALL);

if (ob_get_length()) ob_clean();

// Set JSON header
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Only POST requests are allowed.");
    }

    // Initialize controller
    $jobPostController = new JobPostController();

    // Get POST data safely
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $job_type = $_POST['job_type'] ?? '';
    $location = $_POST['location'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    $num_kids = $_POST['num_kids'] ?? 0;
    $start_hour = $_POST['start_hour'] ?? '';
    $end_hour = $_POST['end_hour'] ?? '';
    $date_range = $_POST['date_range'] ?? '';

    // You can add validations here if needed (e.g., required fields)

    $jobPostController = new JobPostController();

    // Collect the data into an array
    $data = [
        'title' => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'location' => $_POST['location'] ?? '',
        'salary' => $_POST['salary'] ?? 0,
        'schedule' => $_POST['job_type'] ?? 'part-time',
        'num_kids' => $_POST['num_kids'] ?? 0,
        'start_hour' => $_POST['start_hour'] ?? '',
        'end_hour' => $_POST['end_hour'] ?? '',
        'date_from' => $_POST['date_from'] ?? '',
        'date_to' => $_POST['date_to'] ?? '',
    ];
     $jobPostId = $jobPostController->createJobPost($data);
    echo json_encode([
        'success' => true,
        'message' => 'Job post created successfully',
        'job_post_id' => $jobPostId,
    ]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
    exit;
}
