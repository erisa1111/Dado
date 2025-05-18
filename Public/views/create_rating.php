<?php
require_once __DIR__ . '/../../App/Controllers/RatingController.php';

use App\Controllers\RatingController;

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Only POST requests are allowed']);
    exit;
}

// Get POST data safely
$job_id = $_POST['job_id'] ?? null;
$reviewer_id = $_POST['reviewer_id'] ?? null;
$rating = $_POST['rating'] ?? null;
$comment = $_POST['comment'] ?? '';


// Validate required parameters
if (!$job_id || !$reviewer_id) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing job_id or reviewer_id']);
    exit;
}

// Instantiate controller
$controller = new RatingController();

// Call createRating method and get result
$result = $controller->createRating($job_id, $reviewer_id, $rating, $comment);

http_response_code($result['http_code']);
unset($result['http_code']);

echo json_encode($result);
