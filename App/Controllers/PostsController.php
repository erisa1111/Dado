<?php
namespace App\Controllers;

use Config\Database;

class PostsController
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function createPost()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $title = $_POST['title'] ?? '';
        $body = $_POST['body'] ?? '';
        $imageUrl = ''; // You'll handle file upload separately

        try {
            $stmt = $this->conn->prepare("CALL create_post(?, ?, ?, ?)");
            $stmt->execute([$userId, $title, $body, $imageUrl]);
            
            header('Location: /home');
            exit();
        } catch (\PDOException $e) {
            error_log("Error creating post: " . $e->getMessage());
            $_SESSION['error'] = "Error creating post";
            header('Location: /home');
            exit();
        }
    }

    public function getPosts()
    {
        try {
            $stmt = $this->conn->query("CALL get_all_posts_with_users()");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching posts: " . $e->getMessage());
            return [];
        }
    }

    public function toggleLike()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $postId = $_POST['post_id'] ?? null;
        $userId = $_SESSION['user_id'];

        if (!$postId) {
            http_response_code(400);
            echo json_encode(['error' => 'Post ID is required']);
            exit();
        }

        try {
            // Check if already liked
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ? AND user_id = ?");
            $checkStmt->execute([$postId, $userId]);
            $alreadyLiked = $checkStmt->fetchColumn();

            if ($alreadyLiked) {
                $stmt = $this->conn->prepare("CALL remove_like(?, ?)");
            } else {
                $stmt = $this->conn->prepare("CALL add_like(?, ?)");
            }
            
            $stmt->execute([$postId, $userId]);
            
            // Get updated like count
            $countStmt = $this->conn->prepare("SELECT COUNT(*) FROM likes WHERE post_id = ?");
            $countStmt->execute([$postId]);
            $likeCount = $countStmt->fetchColumn();

            echo json_encode(['success' => true, 'liked' => !$alreadyLiked, 'like_count' => $likeCount]);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function addComment()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit();
        }

        $postId = $_POST['post_id'] ?? null;
        $userId = $_SESSION['user_id'];
        $comment = $_POST['comment'] ?? '';
        $parentCommentId = $_POST['parent_comment_id'] ?? null;

        if (!$postId || empty($comment)) {
            http_response_code(400);
            echo json_encode(['error' => 'Post ID and comment are required']);
            exit();
        }

        try {
            $stmt = $this->conn->prepare("CALL add_comment(?, ?, ?, ?)");
            $stmt->execute([$postId, $userId, $comment, $parentCommentId]);
            
            // Get the new comment with user info
            $newCommentStmt = $this->conn->prepare("
                SELECT c.*, u.name, u.surname, u.profile_picture, u.username 
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.id = LAST_INSERT_ID()
            ");
            $newCommentStmt->execute();
            $newComment = $newCommentStmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'comment' => $newComment]);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }

    public function getComments($postId)
    {
        try {
            $stmt = $this->conn->prepare("CALL get_comments_for_post(?)");
            $stmt->execute([$postId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching comments: " . $e->getMessage());
            return [];
        }
    }
}