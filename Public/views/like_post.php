<?php
// Turn off all error reporting in production
// error_reporting(0);

// For debugging only - remove in production
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (ob_get_length()) ob_clean();
// Set headers FIRST
header('Content-Type: application/json');

try {
require_once __DIR__ . '/../../App/Controllers/PostsController.php';
    
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

  

    $controller = new App\Controllers\PostsController();
    
   if (basename($_SERVER['SCRIPT_NAME']) === 'like_post.php') {

        $postId = $input['post_id'] ?? null;
        if (!$postId) throw new Exception('Missing post_id');
        echo $controller->toggleLike();
    } elseif ($_SERVER['SCRIPT_NAME'] === '/handle_comment.php') {
        $postId = $input['post_id'] ?? null;
        $comment = $input['comment'] ?? null;
        if (!$postId || !$comment) throw new Exception('Missing post_id or comment');
        echo $controller->addComment();
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error' => true
    ]);
    exit;
}