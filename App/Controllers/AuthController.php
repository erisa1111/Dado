<?php
namespace App\Controllers;

use App\Models\User;
use App\Helpers\Validation;

class AuthController
{
    public function index()
    {
        // Show welcome page or redirect to login
        header('Location: /login');
        exit();
    }

    public function signup()
{
    $userModel = new User();

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $phone_number = $_POST['phone_number'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $location = $_POST['location'];
    $gender = $_POST['gender'];
    $user_type = $_POST['role']; // 'nanny' or 'parent'

    // Map user type to role_id
    $role_id = ($user_type === 'nanny') ? 2 : 0;

    if ($user_type == 'nanny') {
        $expected_salary = $_POST['expected_salary'];
        $experience = $_POST['experience'];
        $schedule = $_POST['schedule'];
    } else {
        $expected_salary = null;
        $experience = null;
        $schedule = null;
    }

    $data = [
        'name' => $name,
        'surname' => $surname,
        'email' => $email,
        'username' => $username,
        'phone_number' => $phone_number,
        'password' => $password,
        'location' => $location,
        'gender' => $gender,
        'role_id' => $role_id, // pass role_id instead
        'expected_salary' => $expected_salary,
        'experience' => $experience,
        'schedule' => $schedule,
    ];

    try {
        $userModel->createUser($data);
        header('Location: views/login.php');
        exit();
    } catch (\Exception $e) {
        echo "Signup failed: " . $e->getMessage();
    }
}


    public function login()
    {
        echo "Login method reached"; // Debugging
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . '/../../Public/views/login.php';
            return;
        }

        // Handle POST login
        // ... your login logic
    }
}
