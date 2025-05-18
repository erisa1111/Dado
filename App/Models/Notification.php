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
// ✅ NEW: Job Post Comments Notifications
    public function getJobPostCommentsNotifications(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("CALL GetPostJobComments(?)");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            while ($stmt->nextRowset()) {}

            foreach ($notifications as &$notification) {
                $notification['commenter_profile_picture'] = !empty($notification['commenter_profile_picture']) 
                    ? $this->profilePicBaseUrl . $notification['commenter_profile_picture'] 
                    : '/assets/img/default-profile.png';
            }

            return $notifications;
        } catch (PDOException $e) {
            error_log("Database error in getJobPostCommentsNotifications: " . $e->getMessage());
            return [];
        }
    }

    // ✅ NEW: Job Post Likes Notifications
    public function getJobPostLikesNotifications(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("CALL GetPostJobLikes(?)");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            while ($stmt->nextRowset()) {}

            foreach ($notifications as &$notification) {
                $notification['liker_profile_picture'] = !empty($notification['liker_profile_picture']) 
                    ? $this->profilePicBaseUrl . $notification['liker_profile_picture'] 
                    : '/assets/img/default-profile.png';
            }

            return $notifications;
        } catch (PDOException $e) {
            error_log("Database error in getJobPostLikesNotifications: " . $e->getMessage());
            return [];
        }
    }

    // ✅ NEW: Job Post Applications Notifications
    public function getJobPostApplicationsNotifications(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("CALL GetJobPostApplications(?)");
            $stmt->execute([$userId]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            while ($stmt->nextRowset()) {}

           foreach ($notifications as &$notification) {
    $notification['nanny_profile_picture'] = !empty($notification['nanny_profile_picture']) 
        ? $this->profilePicBaseUrl . $notification['nanny_profile_picture'] 
        : '/assets/img/default-profile.png';

    // 🛠 Rename fields for frontend compatibility
    $notification['applicant_name'] = $notification['nanny_name'];
    $notification['applicant_surname'] = $notification['nanny_surname'];
    $notification['applicant_profile_picture'] = $notification['nanny_profile_picture'];

    // (Optional) unset if you want to clean up
    unset($notification['nanny_name'], $notification['nanny_surname'], $notification['nanny_profile_picture']);
}

            return $notifications;
        } catch (PDOException $e) {
            error_log("Database error in getJobPostApplicationsNotifications: " . $e->getMessage());
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
            ? '/../../Public/assets/uploads/pfps/' . $c['commenter_profile_picture'] 
            : '/assets/default-profile.png';
    }

    $likes = $this->getLikesNotifications($userId);
    foreach ($likes as &$l) {
        $l['type'] = 'like';
        $l['liker_profile_picture'] = $l['liker_profile_picture'] 
            ? '/../../Public/assets/uploads/pfps/' . $l['liker_profile_picture'] 
            : '/assets/default-profile.png';
    }

    
    // 💼 Job post comments
    $jobComments = $this->getJobPostCommentsNotifications($userId);
    foreach ($jobComments as &$jc) {
        $jc['type'] = 'job_comment';
    }

    // 👍 Job post likes
    $jobLikes = $this->getJobPostLikesNotifications($userId);
    foreach ($jobLikes as &$jl) {
        $jl['type'] = 'job_like';
    }

    // 📩 Job applications
    $jobApplications = $this->getJobPostApplicationsNotifications($userId);
    foreach ($jobApplications as &$ja) {
        $ja['type'] = 'job_application';
    }

    $all = array_merge($comments, $likes,  $jobComments, $jobLikes, $jobApplications);
    usort($all, function($a, $b) {
        return strtotime($b['created_at']) <=> strtotime($a['created_at']);
    });

    return $all;
    }

    public function acceptApplication(int $applicationId): bool
{
    try {
        $stmt = $this->db->prepare("CALL AcceptApplication(?)");
        $stmt->execute([$applicationId]);
        // Clear any extra result sets
        while ($stmt->nextRowset()) {}
        return true;
    } catch (PDOException $e) {
        error_log("Database error in acceptApplication: " . $e->getMessage());
        return false;
    }
}

public function createJobFromApplication(int $applicationId): bool
{
    try {
        $stmt = $this->db->prepare("CALL CreateJobFromApplication(?)");
        $stmt->execute([$applicationId]);
        // Clear any extra result sets
        while ($stmt->nextRowset()) {}
        return true;
    } catch (PDOException $e) {
        error_log("Database error in createJobFromApplication: " . $e->getMessage());
        return false;
    }
}
public function declineApplication(int $applicationId): bool
{
    try {
        $stmt = $this->db->prepare("CALL DeclineApplication(?)");
        $stmt->execute([$applicationId]);
        // Clear any extra result sets
        while ($stmt->nextRowset()) {}
        return true;
    } catch (PDOException $e) {
        error_log("Database error in declineApplication: " . $e->getMessage());
        return false;
    }
}

public function getAcceptedApplicationNotification(int $nannyId, int $jobPostId): ?array
{
    try {
        // Përgatit thirrjen e procedurës me parametra IN dhe OUT
        $stmt = $this->db->prepare("CALL isApplicationAccepted(:inNannyId, :inJobPostId, @outResult)");
        $stmt->bindParam(':inNannyId', $nannyId, PDO::PARAM_INT);
        $stmt->bindParam(':inJobPostId', $jobPostId, PDO::PARAM_INT);
        $stmt->execute();
        while ($stmt->nextRowset()) {} // pastron multi-results

        // Lexon rezultatin nga variabla OUT
        $resultQuery = $this->db->query("SELECT @outResult as isAccepted");
        $result = $resultQuery->fetch(PDO::FETCH_ASSOC);

        if ($result && $result['isAccepted'] == 1) {
            return [
                'message' => 'Your application has been accepted!',
                'nanny_id' => $nannyId,
                'job_post_id' => $jobPostId,
                'type' => 'application_status',
                'status' => 'accepted',
                'created_at' => date('Y-m-d H:i:s'),
            ];
        } else {
            return null; // nuk ka pranim, nuk kthehet notif
        }
    } catch (PDOException $e) {
        error_log("Database error in getAcceptedApplicationNotification: " . $e->getMessage());
        return null;
    }
}

}
