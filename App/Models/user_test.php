<?php
require_once __DIR__ . '/User.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\User;

echo "<h1>Testing User Model</h1>";

$userModel = new User();

// 1. Test isUsernameTaken()
echo "<h2>Testing isUsernameTaken()</h2>";
$username = 'mer'; // Replace with an existing username in your DB
$isTaken = $userModel->isUsernameTaken($username);
echo "Username '{$username}' taken: " . ($isTaken ? 'Yes' : 'No') . "<br>";

// 2. Test getUserByEmail()
echo "<h2>Testing getUserByEmail()</h2>";
$email = 'erisamatoshi@gmail.com'; // Replace with an existing email in your DB
$user = $userModel->getUserByEmail($email);
if ($user) {
    echo "User found: " . htmlspecialchars($user['name']) . " " . htmlspecialchars($user['surname']) . "<br>";
} else {
    echo "No user found with email '{$email}'<br>";
}

// 3. Test createUser()
echo "<h2>Testing createUser()</h2>";
$newUserData = [
    'name' => 'Test',
    'surname' => 'User',
    'email' => 'testuser' . rand(1000, 9999) . '@example.com',
    'username' => 'testuser' . rand(1000, 9999),
    'phone_number' => '1234567890',
    'password' => password_hash('Test@1234', PASSWORD_BCRYPT),
    'location' => 'Testville',
    'gender' => 'F',
    'role_id' => 0,
    'expected_salary' => null,
    'experience' => null,
    'schedule' => null,
];
try {
    $userModel->createUser($newUserData);
    echo "User '{$newUserData['username']}' created successfully.<br>";
} catch (Exception $e) {
    echo "Error creating user: " . $e->getMessage() . "<br>";
}

// 4. Test getProfile()
echo "<h2>Testing getProfile()</h2>";
$userId = 1; // Replace with a valid user ID
$profile = $userModel->getProfile($userId);
if ($profile) {
    echo "Profile for user ID {$userId}:<br>";
    echo "<pre>" . print_r($profile, true) . "</pre>";
} else {
    echo "No profile found for user ID {$userId}<br>";
}

// 5. Test updateProfile()
echo "<h2>Testing updateProfile()</h2>";
$updateData = [
    'username' => 'updated_username',
    'name' => 'Updated',
    'surname' => 'User',
    'location' => 'Updatedville',
    'phone_number' => '0987654321',
    'email' => 'updateduser@example.com',
    'bio' => 'This is an updated bio.',
    'profile_picture' => 'updated_picture.jpg',
];
try {
    $userModel->updateProfile($userId, $updateData);
    echo "Profile for user ID {$userId} updated successfully.<br>";
} catch (Exception $e) {
    echo "Error updating profile: " . $e->getMessage() . "<br>";
}

// 6. Test storeVerificationToken() and getUserByVerificationToken()
echo "<h2>Testing storeVerificationToken() and getUserByVerificationToken()</h2>";
$token = bin2hex(random_bytes(16));
try {
    $userModel->storeVerificationToken($userId, $token);
    echo "Verification token stored for user ID {$userId}.<br>";
    $userByToken = $userModel->getUserByVerificationToken($token);
    if ($userByToken) {
        echo "User retrieved by token: " . htmlspecialchars($userByToken['username']) . "<br>";
    } else {
        echo "No user found with token '{$token}'<br>";
    }
} catch (Exception $e) {
    echo "Error handling verification token: " . $e->getMessage() . "<br>";
}

// 7. Test verifyUser()
echo "<h2>Testing verifyUser()</h2>";
try {
    $userModel->verifyUser($token);
    echo "User with token '{$token}' verified successfully.<br>";
} catch (Exception $e) {
    echo "Error verifying user: " . $e->getMessage() . "<br>";
}
?>