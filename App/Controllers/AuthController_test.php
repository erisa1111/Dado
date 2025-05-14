<?php
session_start();

// Include dependencies and controller
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../../App/Models/User.php';
require_once __DIR__ . '/../../App/Helpers/Validation.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust path if needed

use App\Controllers\AuthController;

echo "<h1>Testing AuthController</h1>";

// Create the controller instance
$authController = new AuthController();


// 1. ✅ Testing checkUsername() method (AJAX simulation)
echo "<h2>Testing checkUsername()</h2>";

$_GET['username'] = 'mer'; // Replace with a username that exists in your DB

ob_start();
$authController->checkUsername(); // Should return JSON like {"taken":true}
$output = ob_get_clean();

echo "Output for checkUsername(): <pre>$output</pre>";


// 2. ✅ Testing signup() method with POST data
echo "<h2>Testing signup()</h2>";

$_POST = [
    'name' => 'Test',
    'surname' => 'User',
    'email' => 'testuser@example.com',
    'username' => 'unique_username_' . rand(100, 999),
    'phone_number' => '1234567890',
    'password' => 'Test@1234',
    'location' => 'Testville',
    'gender' => 'F',
    'role' => 'parent', // or 'nanny'
    // These are nanny-specific fields (ignored if role is parent)
    'expected_salary' => '1000',
    'experience' => '2 years',
    'schedule' => 'full-time'
];

// Capture the signup output
ob_start();
$authController->signup(); // Should try to create a new user
$output = ob_get_clean();

echo "Output for signup(): <pre>$output</pre>";

?>