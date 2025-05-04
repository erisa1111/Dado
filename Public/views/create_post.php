<?php
require_once '/Users/macair/Desktop/dadodado/App/Models/Post.php';
require_once '/Users/macair/Desktop/dadodado/App/Controllers/PostsController.php';
require_once '/Users/macair/Desktop/dadodado/Config/Database.php';

use App\Controllers\PostsController;
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (ob_get_length()) ob_clean();

// Set JSON header
header('Content-Type: application/json');

try {
    // Check if it's a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Only POST requests are allowed.");
    }

    // Initialize PostsController
    $postsController = new PostsController();

    // Get POST data
    $content = $_POST['content'] ?? '';
    $title = $_POST['title'] ?? 'New Post';

    // Handle file uploads (if any)
    $uploadedFiles = [];
    if (!empty($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] === UPLOAD_ERR_OK) {
                $uploadedFiles[] = [
                    'tmp_name' => $tmpName,
                    'name' => $_FILES['images']['name'][$key],
                    'type' => $_FILES['images']['type'][$key],
                    'size' => $_FILES['images']['size'][$key],
                ];
            }
        }
    }

    // Create the post
    $postId = $postsController->createPost($title, $content, $uploadedFiles);

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'post_id' => $postId,
    ]);
    exit;

} catch (Exception $e) {
    // Error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
    ]);
    exit;
}

/*header('Content-Type: application/json');

try {
    $controller = new PostsController();
    $result = $controller->createPost();
    
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Post created successfully' : 'Failed to create post'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}*/