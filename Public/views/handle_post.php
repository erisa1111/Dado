<?php
require_once __DIR__ . '/../../App/Controllers/PostsController.php';
use App\Controllers\PostsController;

header('Content-Type: application/json');
$postsController = new PostsController();

$action = $_GET['action'] ?? '';

try {
    if ($action === 'editPost') {
        $postId = $_POST['post_id'];
        $content = $_POST['content'];

        $imageUrl = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $tmpName = $_FILES['image']['tmp_name'];
            $imageName = uniqid() . '_' . basename($_FILES['image']['name']);
            $destination = $uploadDir . $imageName;

            if (move_uploaded_file($tmpName, $destination)) {
                $imageUrl = $destination;
            }
        }

        $success = $postsController->editPost($postId, $content, $imageUrl); // update controller method
        echo json_encode(['success' => $success]);
    } elseif ($action === 'deletePost') {
    $input = json_decode(file_get_contents('php://input'), true);
    $postId = $input['post_id'] ?? null;

    if (!$postId) {
        throw new Exception('Post ID is missing');
    }

    $success = $postsController->deletePost($postId);
    echo json_encode(['success' => $success]);
}
 else {
        throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
