<?php
namespace App\Controllers;

require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\Post;
use Config\Database;

class PostsController
{
    private $postModel;

    public function __construct()
    {
        session_start(); // Add this line

        $database = new Database();
        $db = $database->connect();
        $this->postModel = new Post($db);
   
    }
  

    public function getPosts()
    {
        return $this->postModel->getAll();
    }

   /* public function createPost($title, $content, $imageUrl)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ensure session is started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Get current user ID
            $userId = $_SESSION['user_id'] ?? null;
            if (!$userId) {
                http_response_code(401); // Unauthorized
                echo json_encode(['error' => 'User not logged in.']);
                exit;
            }
    
            // Sanitize and assign form inputs
            $title = htmlspecialchars(trim($_POST['title'] ?? 'New Post'));
            $content = htmlspecialchars(trim($_POST['content'] ?? ''));
    
            // Handle image upload
            $imageUrl = null;
            if (isset($_FILES['images']) && $_FILES['images']['error'] === UPLOAD_ERR_OK) {
                $imageUrl = $this->handleImageUpload($_FILES['images']);
            }
    
            // Save to database
            $success = $this->postModel->create($userId, $title, $content, $imageUrl);
    
            // Respond to request
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success]);
                exit;
            } else {
                header('Location: /home');
                exit;
            }
        } else {
            http_response_code(405); // Method not allowed
            echo "Invalid request method.";
            exit;
        }
        /*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get current user ID from session
            $userId = $_SESSION['user_id'] ?? null;
            $title = $_POST['title'] ?? 'New Post'; // Default title if not provided
            $content = $_POST['content'] ?? '';
            
            // Handle image upload
            $imageUrl = $this->handleImageUpload($_FILES['images'] ?? null);

            $this->postModel->create($userId, $title, $content, $imageUrl);
            
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header('Location: /home');
                exit;
            }
        }*/
  //  }

  public function createPost($title, $content, $images)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo "Invalid request method.";
        exit;
    }

    // Start session if needed
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        throw new \Exception('User not logged in.');
    }

    $imageUrl = $this->handleImageUpload($_FILES['images'] ?? null);

    // Call model's create method
    $postId = $this->postModel->create($userId, $title, $content, $imageUrl);

    return $postId;
}


    public function toggleLike()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;
            
            if (!$postId || !$userId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Invalid request']);
                exit;
            }

            // Check if already liked (you might need to add a method in Post model for this)
            $isLiked = $this->isPostLiked($postId, $userId);
            
            if ($isLiked) {
                $this->postModel->unlike($postId, $userId);
                $action = 'unliked';
            } else {
                $this->postModel->like($postId, $userId);
                $action = 'liked';
            }

            // Get updated like count
            $likeCount = $this->getLikeCount($postId);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'action' => $action,
                'like_count' => $likeCount
            ]);
            exit;
        }
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;
            $comment = $_POST['comment'] ?? '';
            $parentCommentId = $_POST['parent_comment_id'] ?? null;

            if (!$postId || !$userId || empty($comment)) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Invalid request']);
                exit;
            }

            $this->postModel->comment($postId, $userId, $comment, $parentCommentId);

            // Get updated comments
            $comments = $this->postModel->getComments($postId);

            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'comments' => $comments
            ]);
            exit;
        }
    }

    private function isPostLiked($postId, $userId)
    {
        // You might need to implement this method in your Post model
        // or use a direct query here
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT 1 FROM likes WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        return (bool)$stmt->fetch();
    }

    private function getLikeCount($postId)
    {
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM likes WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return $result['count'];
    }

    private function handleImageUpload($files)
    {
        if (!$files || empty($files['name'][0])) {
            return null;
        }

        // Handle single or multiple files
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Public/uploads/'; // Absolute server path
        $publicPath = '/Public/uploads/'; 
        
        // Ensure directory exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Process each file
        foreach ($files['tmp_name'] as $key => $tmpName) {
            $fileName = basename($files['name'][$key]);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = uniqid() . '.' . $fileExt;
            $uploadPath = $uploadDir . $newFileName;

            // Validate and move file
            if (move_uploaded_file($tmpName, $uploadPath)) {
                $uploadedImages[] = '/uploads/' . $newFileName;
            }
        }

        // Return first image URL or null if no images
        return !empty($uploadedImages)? $publicPath . $newFileName : null;
    }

    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}