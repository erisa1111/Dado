<?php

use App\Models\User;
require_once __DIR__ . '/../Models/User.php';


class ProfileController {

    public function showProfile(){
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $userModel = new User();
        $userData = $userModel->getProfile($_SESSION['user_id']);

        require_once __DIR__ . '/views/profile.php';
    }

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
