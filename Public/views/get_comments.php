<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../App/Controllers/CommentsController.php';

use App\Controllers\CommentsController;

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $postId = $_GET['post_id'] ?? null;
    if (!$postId) {
        throw new Exception("Post ID required");
    }

    // Create the controller object and call the method to handle the request
    $controller = new CommentsController();
    $controller->getCommentsForPost($postId);  // This will output the JSON response directly
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}

