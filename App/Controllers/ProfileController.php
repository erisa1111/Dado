<?php
require_once __DIR__ . '/../Models/User.php';

class ProfileController {

    public function viewProfile($userId) {
        $userModel = new User();
        $user = $userModel->getUserById($userId);

        include '../Views/profile.php';
    }

    public function updateProfile($userId, $newData) {
        $userModel = new User();
        $userModel->updateUserProfile($userId, $newData);

        header('Location: /profile');
        exit();
    }
}
?>
