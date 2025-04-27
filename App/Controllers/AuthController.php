<?php
// Inside Controllers/AuthController.php

class AuthController {
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $phone_number = $_POST['phone_number'];
            $password = $_POST['password'];
            $role_id = $_POST['role_id'];
            $location = $_POST['location'];

            // Hash the password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database (you need to have the User model)
            $userModel = new User();
            $userModel->createUser($role_id, $name, $surname, $email, $phone_number, $password_hash, $location);

            // Redirect to login page after successful signup
            header('Location: /login');
            exit;
        }
    }
}
?>