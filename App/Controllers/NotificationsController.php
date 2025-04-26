<?php
require_once __DIR__ . '/../Models/Notification.php';

class NotificationsController {

    public function showNotifications($userId) {
        $notificationModel = new Notification();
        $notifications = $notificationModel->getNotificationsByUserId($userId);

        include '../Views/notifications.php';
    }
}
?>
