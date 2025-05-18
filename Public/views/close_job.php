<?php
require_once __DIR__ . '/../../App/Controllers/JobPostController.php';
require_once __DIR__ . '/../../Config/Database.php';
use App\Controllers\JobPostController;
$controller = new JobPostController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'closeJobPost') {
    $controller->closeJobPost();
    exit;
}

// If not a valid request
header('Content-Type: application/json');
echo json_encode(['success' => false, 'message' => 'Invalid request']);