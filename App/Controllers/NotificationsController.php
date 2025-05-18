<?php

namespace App\Controllers;

use App\Models\Notifications;

require_once __DIR__ . '/../../App/Models/Notification.php';

class NotificationsController
{
    private $notificationsModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->notificationsModel = new Notifications();
    }

    /**
     * Display the notifications page (comments and likes)
     */
     public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit();
        }

        $notifications = $this->notificationsModel->getAllNotifications($userId);
        $jobComments = $this->notificationsModel->getJobPostCommentsNotifications($userId);
        $jobLikes = $this->notificationsModel->getJobPostLikesNotifications($userId);
        $jobApplications = $this->notificationsModel->getJobPostApplicationsNotifications($userId);

        include __DIR__ . '/../../Public/views/notifications.php';
    }

    /**
     * Return notifications as JSON (for AJAX refresh)
     */
    public function fetch()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $notifications = $this->notificationsModel->getAllNotifications($userId);
        echo json_encode(['success' => true, 'notifications' => $notifications]);
    }
      public function fetchJobComments()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $comments = $this->notificationsModel->getJobPostCommentsNotifications($userId);
        echo json_encode(['success' => true, 'job_comments' => $comments]);
    }

    /**
     * Return job post likes notifications as JSON
     */
    public function fetchJobLikes()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $likes = $this->notificationsModel->getJobPostLikesNotifications($userId);
        echo json_encode(['success' => true, 'job_likes' => $likes]);
    }

    /**
     * Return job post application notifications as JSON
     */
    public function fetchJobApplications()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $applications = $this->notificationsModel->getJobPostApplicationsNotifications($userId);
        echo json_encode(['success' => true, 'job_applications' => $applications]);
    }
     public function acceptApplication()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        // Get application ID from POST (adjust if you use GET)
        $applicationId = $_POST['application_id'] ?? null;

        if (!$applicationId) {
            echo json_encode(['success' => false, 'message' => 'Missing application ID']);
            return;
        }

        $result = $this->notificationsModel->acceptApplication((int)$applicationId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Application accepted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to accept application']);
        }
    }
    public function createJobFromApplication()
{
    header('Content-Type: application/json');
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }

    $applicationId = $_POST['application_id'] ?? null;
    if (!$applicationId) {
        echo json_encode(['success' => false, 'message' => 'Missing application ID']);
        return;
    }

    $result = $this->notificationsModel->createJobFromApplication((int)$applicationId);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Job contract created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create job contract']);
    }
}

public function acceptAndCreateJob()
{
    header('Content-Type: application/json');
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }

    $applicationId = $_POST['application_id'] ?? null;
    if (!$applicationId) {
        echo json_encode(['success' => false, 'message' => 'Missing application ID']);
        return;
    }

    $accepted = $this->notificationsModel->acceptApplication((int)$applicationId);
    if (!$accepted) {
        echo json_encode(['success' => false, 'message' => 'Failed to accept application']);
        return;
    }
error_log("Attempting to create job from application ID: $applicationId");
$jobCreated = $this->notificationsModel->createJobFromApplication((int)$applicationId);
error_log("Job creation result: " . ($jobCreated ? 'SUCCESS' : 'FAILURE'));
    if (!$jobCreated) {
        echo json_encode(['success' => false, 'message' => 'Application accepted, but failed to create job']);
        return;
    }

    echo json_encode(['success' => true, 'message' => 'Application accepted and job contract created']);
}

    /**
     * Decline a job application by changing its status to 'declined'
     */
    public function declineApplication()
    {
        header('Content-Type: application/json');
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        // Get application ID from POST (adjust if you use GET)
        $applicationId = $_POST['application_id'] ?? null;

        if (!$applicationId) {
            echo json_encode(['success' => false, 'message' => 'Missing application ID']);
            return;
        }

        $result = $this->notificationsModel->declineApplication((int)$applicationId);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Application declined']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to decline application']);
        }
    }

    public function fetchAcceptedApplicationNotification()
{
    header('Content-Type: application/json');
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        echo json_encode(['success' => false, 'message' => 'Not authenticated']);
        return;
    }

    // Get nanny ID and job post ID from GET or POST (adjust based on your frontend logic)
    $nannyId = $_GET['nanny_id'] ?? $_POST['nanny_id'] ?? null;
    $jobPostId = $_GET['job_post_id'] ?? $_POST['job_post_id'] ?? null;

    if (!$nannyId || !$jobPostId) {
        echo json_encode(['success' => false, 'message' => 'Missing nanny ID or job post ID']);
        return;
    }

    $notification = $this->notificationsModel->getAcceptedApplicationNotification((int)$nannyId, (int)$jobPostId);

    if ($notification) {
        echo json_encode(['success' => true, 'notification' => $notification]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No accepted application notification found']);
    }
}

public function checkApplicationAcceptance()
{
    header('Content-Type: application/json');
    
    // You can validate the session here, or skip it if not necessary
    $nannyId = $_GET['nanny_id'] ?? null;
    $jobPostId = $_GET['job_post_id'] ?? null;

    if (!$nannyId || !$jobPostId) {
        echo json_encode(['success' => false, 'message' => 'Missing parameters']);
        return;
    }

    $notification = $this->notificationsModel->getAcceptedApplicationNotification((int)$nannyId, (int)$jobPostId);

    if ($notification) {
        echo json_encode(['success' => true, 'notification' => $notification]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No acceptance notification found']);
    }
}
}