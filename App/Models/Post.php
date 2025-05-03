<?php
namespace App\Models;

use Config\Database;

class Post
{
    private $db;

    public function __construct($db)
    {
        $database = new Database();
        $this->db = $database->connect(); 
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("CALL get_all_posts_with_users()");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($userId, $title, $body, $imageUrl = null)
    {
        $stmt = $this->db->prepare("CALL create_post(?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $body, $imageUrl]);
        return true;
    }

    public function like($postId, $userId)
    {
        $stmt = $this->db->prepare("CALL add_like(?, ?)");
        return $stmt->execute([$postId, $userId]);
    }

    public function unlike($postId, $userId)
    {
        $stmt = $this->db->prepare("CALL remove_like(?, ?)");
        return $stmt->execute([$postId, $userId]);
    }

    public function comment($postId, $userId, $comment, $parentCommentId = null)
    {
        $stmt = $this->db->prepare("CALL add_comment(?, ?, ?, ?)");
        return $stmt->execute([$postId, $userId, $comment, $parentCommentId]);
    }

    public function getComments($postId)
    {
        $stmt = $this->db->prepare("CALL get_comments_for_post(?)");
        $stmt->execute([$postId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}