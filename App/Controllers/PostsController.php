<?php
namespace App\Controllers;
use Exception; 

require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\Post;
use Config\Database;

class PostsController
{
    private $postModel;
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}// Add this line

        $database = new Database();
        $this->db = $database->connect();
        $this->postModel = new Post($this->db);
   
    }
  

    public function getPosts()
    {
        return $this->postModel->getAll();
    }

  public function editPost($postId, $content, $imageUrl = null) {
    $stmt = $this->db->prepare("CALL update_post(:post_id, :new_content, :new_image)");
    $stmt->bindParam(':post_id', $postId);
    $stmt->bindParam(':new_content', $content);
    $stmt->bindParam(':new_image', $imageUrl);
    return $stmt->execute();
}



public function deletePost($postId) {
   
    return $this->postModel->deletePost($postId);
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
        throw new Exception('User not logged in.');
    }

    $imageUrl = $this->handleImageUpload($_FILES['images'] ?? null);

    // Call model's create method
    $postId = $this->postModel->create($userId, $title, $content, $imageUrl);

    return $postId;
}

public function toggleLike()
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $postId = $input['post_id'] ?? null;
        
        $userId = $_SESSION['user_id'] ?? null;

        if (!$postId || !$userId) {
            return json_encode([
                'success' => false,
                'message' => 'Invalid data'
            ]);
        }

        // Your existing like logic here...
        $isLiked = $this->isPostLiked($postId, $userId);

        if ($isLiked) {
            $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = ? AND user_id = ?");
            $stmt->execute([$postId, $userId]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (?, ?)");
            $stmt->execute([$postId, $userId]);
        }

        $likeCount = $this->getLikeCount($postId);

        return json_encode([
            'success' => true,
            'like_count' => $likeCount,
            'is_liked' => !$isLiked
        ]);

    } catch (Exception $e) {
        return json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
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

public function handleRequest()
{
    $action = $_GET['action'] ?? '';
    
    switch ($action) {
        case 'getCommentsForPost':
            $postId = $_GET['post_id'] ?? null;
            if ($postId) {
                $this->getCommentsForPost($postId);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Post ID required']);
            }
            break;
            
        // Add other actions here...
            
        default:
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Action not found']);
    }
}

public function getCommentsForPost($postId)
{
    header('Content-Type: application/json');
    
    try {
        // Verify post exists without throwing error if it doesn't
        $postCheck = $this->db->prepare("SELECT id FROM posts WHERE id = ?");
        $postCheck->execute([$postId]);
        
        if (!$postCheck->fetch()) {
            // Return empty array instead of 404
            echo json_encode([
                'success' => true,
                'comments' => []
            ]);
            return;
        }

        $stmt = $this->db->prepare("CALL get_comments_for_post(?)");
        $stmt->execute([$postId]);
        $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'comments' => $comments ?: []
        ]);
    } catch (Exception $e) {
        // Still return empty array on error
        echo json_encode([
            'success' => true,
            'comments' => []
        ]);
    }
}

    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


}

