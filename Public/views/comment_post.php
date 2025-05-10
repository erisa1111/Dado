<?php
header('Content-Type: application/json');
require_once '/Users/macair/Desktop/dadodado/App/Controllers/PostsController.php';

try {
    session_start();
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new Exception('Invalid input data');
    }

    $postId = $input['post_id'] ?? null;
    $comment = $input['comment'] ?? null;
    
    if (!$postId || !$comment) {
        throw new Exception('Post ID and comment are required');
    }

    $controller = new App\Controllers\PostsController();
    $controller->addComment();
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}