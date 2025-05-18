<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Clear any previous output buffer
if (ob_get_length()) ob_clean();

// Always set headers first
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../../App/Controllers/JobPostController.php';
    
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    // Validate job_post_id
    $postId = $input['job_post_id'] ?? null;
    if (!$postId) {
        throw new Exception('Missing job_post_id');
    }

    // Instantiate the controller
    $controller = new App\Controllers\JobPostController();
    
    // Call the like toggle method and return its response
    echo $controller->toggleJobLike($postId);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]);
    exit;
}
