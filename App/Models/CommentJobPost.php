<?php
namespace App\Models;
use Exception;
use Config\Database;

class CommentJobPost
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getCommentsForJobPost($jobPostId)
    {
        try {
            $stmt = $this->db->prepare("CALL get_comments_for_job_post(?)");
            $stmt->execute([$jobPostId]);
            $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $comments ?: [];
        } catch (Exception $e) {
            error_log('Error fetching comments for job post: ' . $e->getMessage());
            return [];
        }
    }

    public function addCommentForJobPost($jobPostId, $userId, $comment, $parentCommentId = null)
    {
        $stmt = $this->db->prepare("CALL add_comment_job_post(?, ?, ?, ?)");
        $stmt->execute([$jobPostId, $userId, $comment, $parentCommentId]);
        $result = $stmt->fetch();
        return $result['comment_id'] ?? null;
    }

    public function deleteCommentForJobPost($commentId, $userId)
    {
        $stmt = $this->db->prepare("CALL delete_comment_job_post(?, ?)");
        $stmt->execute([$commentId, $userId]);
        $result = $stmt->fetch();
        return $result['affected_rows'] > 0;
    }

    public function updateCommentForJobPost($commentId, $userId, $newComment)
    {
        $stmt = $this->db->prepare("CALL update_comment_for_job_post(?, ?, ?)");
        $stmt->execute([$commentId, $userId, $newComment]);
        $result = $stmt->fetch();
        return $result['affected_rows'] > 0;
    }

    public function getCommentByIdForJobPost($commentId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.username, u.profile_picture 
            FROM job_post_comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.id = ?
        ");
        $stmt->execute([$commentId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getCommentCountForJobPost($jobPostId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM job_post_comments WHERE job_post_id = ?");
        $stmt->execute([$jobPostId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
