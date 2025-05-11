<?php
require_once '/Users/macair/Desktop/dadodado/App/Controllers/PostsController.php';

use App\Controllers\PostsController;

header('Content-Type: application/json');
$postsController = new PostsController();

$data = json_decode(file_get_contents('php://input'), true);
$action = $_GET['action'] ?? '';

try {
    if ($action === 'editPost') {
        $postId = $data['post_id'];
        $content = $data['content'];
        $success = $postsController->editPost($postId, $content);
        echo json_encode(['success' => $success]);
    } elseif ($action === 'deletePost') {
        $postId = $data['post_id'];
        $success = $postsController->deletePost($postId);
        echo json_encode(['success' => $success]);
    } else {
        throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
