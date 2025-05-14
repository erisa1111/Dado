<?php
namespace App\Controllers;
require_once __DIR__ . '/../Helpers/Validation.php';

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

    public function checkUsername()
{
    $userModel = new User();
    $username = $_GET['username'] ?? '';
    $isTaken = $userModel->isUsernameTaken($username);
    echo json_encode(['taken' => $isTaken]);
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

    // NEW: Check if username is already taken
    if ($userModel->isUsernameTaken($username)) {
    $redirectUrl = ($user_type == 'parent') ? '/views/signup_p.php?error=username' : '/views/signup_n.php?error=username';
    header("Location: $redirectUrl");
    exit();
}
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
        'role_id' => $role_id,
        'expected_salary' => $expected_salary,
        'experience' => $experience,
        'schedule' => $schedule,
    ];

    $validationResult = Validation::validateSignupData($data);

    if ($validationResult !== true) {
        echo "<div class='error-message'>" . $validationResult . "</div>";
        return;
    }

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
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        include __DIR__ . '/../../Public/views/login.php';
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Use the Config\Database class to get the connection
        $db = new \Config\Database();  
        $conn = $db->connect(); // Get the PDO connection

        // Prepare the stored procedure call to fetch user by username
        $sql = "CALL Login_Uer(?)";  // Call the procedure with the username as a parameter
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $username, \PDO::PARAM_STR);
        $stmt->execute();

        // Fetch result and check login
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Debugging: print out the user array to check if 'password_hash' is included
        var_dump($user); // or echo '<pre>' . print_r($user, true) . '</pre>';

        if ($user) {
            // Check if password_hash exists before verifying
            if (isset($user['password_hash']) && password_verify($password, $user['password_hash'])) {
                // Password is correct, start the session
                session_start();
                $_SESSION['user_id'] = $user['id']; // Use 'id' instead of 'user_id'
                $_SESSION['username'] = $user['username'];

                // Redirect to the dashboard or home page
                header('Location: views/home.php'); // Replace with your actual redirect page
                exit();
            } else {
                echo "Invalid username or password!";
               // header('Location: views/login.php');
            }
        } else {
            echo "Invalid username or password!";
           // header('Location: views/login.php');
        }
    }
}


    public function logout(){
        // Start the session to access session variables
        session_start();

        // Destroy the session and unset all session variables
        session_unset();    // Removes all session variables
        session_destroy();  // Destroys the session data

        // Redirect to the index page (or the login page)
        header('Location: index.php');  // Adjust this to your index URL
        exit();
    }
   

}