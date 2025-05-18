<?php
namespace App\Models;

use Config\Database;

class Post
{
    private $db;

    public function __construct($db = null)
{
    if ($db !== null) {
        $this->db = $db;
    } else {
        $database = new Database();
        $this->db = $database->connect();
    }
}


    public function getAll()
    {
        $stmt = $this->db->prepare("CALL get_all_posts_with_users()");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create($userId, $title, $body, $imageUrl = null)
    {
        /*var_dump($userId, $title, $body, $imageUrl);
        $stmt = $this->db->prepare("CALL create_post(?, ?, ?, ?)");
        $stmt->execute([$userId, $title, $body, $imageUrl]);
        return true;*/
        $stmt = $this->db->prepare("CALL create_post(?, ?, ?, ?)");
    $stmt->execute([$userId, $title, $body, $imageUrl]);

    // Assume your SP does SELECT LAST_INSERT_ID(); after insert
    $result = $stmt->fetch();
    return $this->db->lastInsertId(); 
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
        public function updatePost($postId, $content) {
        $stmt = $this->db->prepare("CALL update_post(:post_id, :content)");
        $stmt->bindParam(':post_id', $postId);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    public function deletePost($postId) {
        $stmt = $this->db->prepare("CALL delete_post(:post_id)");
        $stmt->bindParam(':post_id', $postId);
        return $stmt->execute();
    }

   public function searchPosts($query) {
        $sql = "SELECT p.*, u.username, u.profile_picture 
                FROM posts p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.body LIKE :queryBody OR p.title LIKE :queryTitle OR u.username LIKE :queryUsername 
                ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $likeQuery = '%' . $query . '%';
        $stmt->bindValue(':queryBody', $likeQuery, \PDO::PARAM_STR);
        $stmt->bindValue(':queryTitle', $likeQuery, \PDO::PARAM_STR);
        $stmt->bindValue(':queryUsername', $likeQuery, \PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPostsByUserId($userId) {
        $query = "
            SELECT 
                p.*,
                u.id AS user_id,
                u.name,
                u.surname,
                u.profile_picture,
                u.username,
                (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS like_count,
                (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS comment_count
            FROM posts p
            JOIN users u ON p.user_id = u.id
            WHERE p.user_id = ?
            ORDER BY p.created_at DESC;
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }




}