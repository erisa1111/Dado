<?php
namespace App\Models;
use Exception;
use Config\Database;

class Comment
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

   public function getCommentsForPost($postId)
{
    try {
        $stmt = $this->db->prepare("CALL get_comments_for_post(?)");
        $stmt->execute([$postId]);
        
        // Fetch all results
        $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if ($comments === false) {
            throw new Exception('No comments found for this post.');
        }
        
        return $comments;
    } catch (Exception $e) {
        // Handle any errors
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
        return false;  // Ensure we return false if there's an issue
    }
}


    public function addComment($postId, $userId, $comment, $parentCommentId = null)
    {
        $stmt = $this->db->prepare("CALL add_comment(?, ?, ?, ?)");
        $stmt->execute([$postId, $userId, $comment, $parentCommentId]);
        $result = $stmt->fetch();
        return $result['comment_id'] ?? null;
    }

    public function deleteComment($commentId, $userId)
    {
        $stmt = $this->db->prepare("CALL delete_comment(?, ?)");
        $stmt->execute([$commentId, $userId]);
        $result = $stmt->fetch();
        return $result['affected_rows'] > 0;
    }

    public function updateComment($commentId, $userId, $newComment)
    {
        $stmt = $this->db->prepare("CALL update_comment(?, ?, ?)");
        $stmt->execute([$commentId, $userId, $newComment]);
        $result = $stmt->fetch();
        return $result['affected_rows'] > 0;
    }

    public function getCommentById($commentId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.profile_picture 
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$commentId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCommentCount($postId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM comments WHERE post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}