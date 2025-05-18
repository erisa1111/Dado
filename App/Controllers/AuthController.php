<?php
namespace App\Controllers;
require_once __DIR__ . '/../Helpers/Validation.php';

use App\Models\User;
use App\Helpers\Validation;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
public function verifyEmail()
{
    $token = $_GET['token'] ?? null;
    if (!$token) {
        header('Location: /login?error=invalid_token');
        exit();
    }

    $userModel = new User();
    if ($userModel->verifyUser($token)) {
        // verifyUser should UPDATE users SET is_verified=1 WHERE token=…
        header('Location: /login?verified=1');
    } else {
        header('Location: /login?error=verification_failed');
    }
    exit();
}
    
private function sendWelcomeEmail($toEmail, $name, $verificationToken)

{
    // Include the Composer autoloader
    require_once __DIR__ . '/../vendor/autoload.php';
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
                ? 'https' 
                : 'http';
    $host   = $_SERVER['HTTP_HOST'];             // e.g. "localhost:8000"
    $baseUrl = "{$scheme}://{$host}";

    // 2) Construct the full verification URL
    $verificationUrl = "{$baseUrl}/verify-email?token={$verificationToken}";
    try {
        $mail = new PHPMailer(true);
    // Server settings - Updated with Gmail config
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'erisamatoshi@gmail.com';
        $mail->Password   = 'csdh napr ujin gtzo';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPDebug  = 0; // Enable verbose debug output

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'DADO');
        $mail->addAddress($toEmail, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Welcome to Our Platform!';
        
        //$verificationUrl = "http://yourdomain.com/verify-email?token=$verificationToken";
        
        $mail->Body = "<h1>Welcome, $name!</h1>
            <p>Thank you for signing up to our platform.</p>
            <p>Please verify your email address by clicking the link below:</p>
            <p><a href='$verificationUrl'>Verify Email Address</a></p>";
        
        $mail->AltBody = "Welcome, $name!\n\nVerify your email: $verificationUrl";

        if (!$mail->send()) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
            return false;
        }
        
        return true;

    } catch (Exception $e) {
        error_log("PHPMailer Exception: " . $e->getMessage());
        return false;
    }
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
       // echo "<div class='error-message'>" . $validationResult . "</div>";
        return;
    }

 try {
        $success = $userModel->createUser($data);

        if ($success) {
            // echo "User created successfully.<br>";

            $userId = $userModel->getLastInsertId();
           // echo "User ID: $userId<br>";

            $verificationToken = bin2hex(random_bytes(32));
           // echo "Verification token: $verificationToken<br>";

            $userModel->storeVerificationToken($userId, $verificationToken);
           // echo "Verification token stored.<br>";

            $emailSent = $this->sendWelcomeEmail($email, $name, $verificationToken);
           // echo "sendWelcomeEmail returned: " . ($emailSent ? "true" : "false") . "<br>";
        } else {
            echo "User creation failed.<br>";
        }

        // Comment out redirect for now so you see above messages
        header('Location: views/login.php');
        exit();

    } catch (\Exception $e) {
        echo "Signup failed: " . $e->getMessage();
    }
}


function login()
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
    // ← Insert the “verified?” check right here:
     if (empty($user['is_verified'])) {
        // user hasn’t clicked the link yet:
        //echo "Please verify your email before logging in.";
        //return;
            header('Location: /login?error=not_verified');
            exit();
        
    }

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