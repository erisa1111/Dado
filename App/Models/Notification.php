<?php

namespace App\Models;

use Config\Database;
use PDO;
use PDOException;
require_once __DIR__ . '/../../Config/Database.php';

class Notifications
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    /**
     * Fetch all comment notifications for posts owned by the given user
     *
     * @param int $userId
     * @return array
     */
    public function getCommentsNotifications(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("CALL GetCommentsNotifications(?)");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // consume any remaining result sets to clear the connection
            while ($stmt->nextRowset()) {}
            return $notifications;
        } catch (PDOException $e) {
            error_log("Database error in getCommentsNotifications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch all like notifications for posts owned by the given user
     *
     * @param int $userId
     * @return array
     */
    public function getLikesNotifications(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("CALL GetLikesNotifications(?)");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            while ($stmt->nextRowset()) {}
            return $notifications;
        } catch (PDOException $e) {
            error_log("Database error in getLikesNotifications: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Optionally merge comments and likes into a unified list, sorted by date
     *
     * @param int $userId
     * @return array
     */
    public function getAllNotifications(int $userId): array
    {
        $comments = $this->getCommentsNotifications($userId);
        // tag each as type 'comment'
        foreach ($comments as &$c) {
            $c['type'] = 'comment';
        }

        $likes = $this->getLikesNotifications($userId);
        // tag each as type 'like'
        foreach ($likes as &$l) {
            $l['type'] = 'like';
        }

        // merge and sort by created_at descending
        $all = array_merge($comments, $likes);
        usort($all, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });

        return $all;
    }
}
