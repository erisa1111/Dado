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
    private $profilePicBaseUrl = '/assets/uploads/pfps/';
    public function getCommentsNotifications(int $userId): array
    {
        $stmt = $this->db->prepare("CALL GetCommentsNotifications(?)");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Clear multi query results, if needed
        while ($stmt->nextRowset()) {}

        foreach ($notifications as &$notification) {
            if (!empty($notification['commenter_profile_picture'])) {
                $notification['commenter_profile_picture'] = $this->profilePicBaseUrl . $notification['commenter_profile_picture'];
            } else {
                $notification['commenter_profile_picture'] = '/assets/img/default-profile.png'; // fallback image url
            }
        }

        return $notifications;
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
        
        // prepend profile picture url for each notification
        foreach ($notifications as &$notification) {
            if (!empty($notification['liker_profile_picture'])) {
                $notification['liker_profile_picture'] = $this->profilePicBaseUrl . $notification['liker_profile_picture'];
            } else {
                $notification['liker_profile_picture'] = '/assets/img/default-profile.png'; // fallback
            }
        }
        
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
    foreach ($comments as &$c) {
        $c['type'] = 'comment';
        $c['commenter_profile_picture'] = $c['commenter_profile_picture'] 
            ? '/assets/uploads/pfps/' . $c['commenter_profile_picture'] 
            : '/assets/default-profile.png';
    }

    $likes = $this->getLikesNotifications($userId);
    foreach ($likes as &$l) {
        $l['type'] = 'like';
        $l['liker_profile_picture'] = $l['liker_profile_picture'] 
            ? '/assets/uploads/pfps/' . $l['liker_profile_picture'] 
            : '/assets/default-profile.png';
    }

    $all = array_merge($comments, $likes);
    usort($all, function($a, $b) {
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    return $all;
    }
}
