<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (ob_get_length()) ob_clean();

require_once __DIR__ . '/../../App/Controllers/CommentsJobPostsController.php';

try {
    session_start();

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || !isset($input['post_id']) || !isset($input['comment'])) {
        throw new Exception('Post ID and comment are required');
    }

    $controller = new App\Controllers\CommentsJobPostsController();
    $controller->addCommentForJobPost(); 

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
