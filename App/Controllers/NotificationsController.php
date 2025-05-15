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
}