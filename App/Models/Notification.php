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
    $profilePic = $this->getUserProfilePicture($notification['commenter_id']);
    $notification['commenter_profile_picture'] = $profilePic 
        ? '/' . ltrim($profilePic, '/\\') 
        : '/assets/img/default-profile.png';
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
    $profilePic = $this->getUserProfilePicture($notification['liker_id']);
    $notification['liker_profile_picture'] = $profilePic 
        ? '/' . ltrim($profilePic, '/\\') 
        : '/assets/img/default-profile.png';
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
    $profilePic = $this->getUserProfilePicture($notification['commenter_id']);
    $notification['commenter_profile_picture'] = $profilePic 
        ? '/' . ltrim($profilePic, '/\\') 
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
    $profilePic = $this->getUserProfilePicture($notification['liker_id']);
    $notification['liker_profile_picture'] = $profilePic 
        ? '/' . ltrim($profilePic, '/\\') 
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
    $profilePic = $this->getUserProfilePicture($notification['nanny_id']);
    $profilePicPath = $profilePic 
        ? '/' . ltrim($profilePic, '/\\') 
        : '/assets/img/default-profile.png';

    $notification['applicant_name'] = $notification['nanny_name'] ?? 'Nanny';
    $notification['applicant_surname'] = $notification['nanny_surname'] ?? '';
    $notification['applicant_profile_picture'] = $profilePicPath;

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
        // $c['commenter_profile_picture'] = $c['commenter_profile_picture'] 
        //     ? '/../../Public/assets/uploads/pfps/' . $c['commenter_profile_picture'] 
        //     : '/assets/default-profile.png';
    }

    $likes = $this->getLikesNotifications($userId);
    foreach ($likes as &$l) {
        $l['type'] = 'like';
        // $l['liker_profile_picture'] = $l['liker_profile_picture'] 
        //     ? '/../../Public/assets/uploads/pfps/' . $l['liker_profile_picture'] 
        //     : '/assets/default-profile.png';
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

   $applicationAcceptances = $this->getAcceptedApplicationsForNanny($userId); // Assuming $userId is nanny
   foreach ($applicationAcceptances as &$aa) {
        $aa['type'] = 'application_acceptance';
        
        // Just add this line to format the parent's profile pic:
       $aa['parent_profile_picture'] = $aa['parent_profile_picture'] 
    ? $aa['parent_profile_picture'] 
    : '/assets/img/dado_profile.webp'; // Match your default
    }


// foreach ($applicationAcceptances as &$aa) {
//     $aa['type'] = 'application_acceptance';
// }

$all = array_merge(
    $comments, $likes, 
    $jobComments, $jobLikes, 
    $jobApplications,
    $applicationAcceptances // ⬅️ add this
);
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
public function getUserProfilePicture(int $userId): ?string
{
    try {
        $stmt = $this->db->prepare("CALL GetUserProfilePicture(?)");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Clear any additional result sets
        while ($stmt->nextRowset()) {}
        
        return $result['profile_picture'] ?? null;
    } catch (PDOException $e) {
        error_log("Error fetching profile picture: " . $e->getMessage());
        return null;
    }
}


public function getAcceptedApplicationsForNanny(int $nannyId): array
{
    try {
        $stmt = $this->db->prepare("CALL GetAcceptedApplicationsForNanny(?)");
        $stmt->execute([$nannyId]);
        $acceptedApps = $stmt->fetchAll(PDO::FETCH_ASSOC);
        while ($stmt->nextRowset()) {}

        $notifications = [];
        foreach ($acceptedApps as $app) {
            // Get profile picture for this specific parent
            $profilePic = $this->getUserProfilePicture($app['parent_id']);
          $profilePicPath = $profilePic 
    ? '/' . ltrim($profilePic, '/\\')  // ensures '/assets/uploads/pfps/...' format
    : '/assets/img/dado_profile.webp'; // default image
            $notifications[] = [
                'message' => 'Your application for "' . ($app['job_title'] ?? 'a job') . '" has been accepted!',
                'nanny_id' => $app['nanny_id'] ?? null,
                'job_post_id' => $app['job_post_id'] ?? null,
                'job_title' => $app['job_title'] ?? null,
                'parent_id' => $app['parent_id'] ?? null,
                'parent_name' => $app['parent_name'] ?? 'Parent',
                'parent_surname' => $app['parent_surname'] ?? '',
              'parent_profile_picture' => $profilePicPath,
                'type' => 'application_acceptance',
                'status' => 'accepted',
                'created_at' => $app['accepted_at'] ?? date('Y-m-d H:i:s'),
            ];
        }
        return $notifications;
    } catch (PDOException $e) {
        error_log("Database error in getAcceptedApplicationsForNanny: " . $e->getMessage());
        return [];
    }
}

}
