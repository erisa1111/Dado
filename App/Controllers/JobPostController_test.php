<?php
use App\Controllers\JobPostController;

//require_once __DIR__ . '/../../vendor/autoload.php'; // adjust if needed
require_once __DIR__ . '/JobPostController.php';
require_once __DIR__ . '/../../App/Models/JobPost.php';
require_once __DIR__ . '/../../Config/Database.php';

// ✅ Simulate session
session_start();
$_SESSION['user_id'] = 86; // change this to a valid nanny user ID in your DB

// ✅ Simulate JSON input
$input = json_encode([
    'job_post_id' => 2 // change this to a valid job_post_id
]);

// ✅ Override php://input for testing (CLI only)
class MockInput {
    public static $data = '';

    public static function set($data) {
        self::$data = $data;
    }

    public static function get() {
        return self::$data;
    }
}

// Monkey-patch php://input (for CLI/testing)
stream_wrapper_unregister('php');
stream_wrapper_register('php', 'MockPhpStream');

class MockPhpStream {
    public function stream_open() { return true; }
    public function stream_read()  { return MockInput::get(); }
    public function stream_write($data) { MockInput::set($data); return strlen($data); }
    public function stream_eof() { return true; }
    public function stream_stat() {}
}

MockInput::set($input);

$_GET['action'] = 'checkIfApplied';
// ✅ Call controller
$controller = new JobPostController();
echo "<h1>Testing JobPostController</h1>";
echo "<h2>checkIfApplied (user_id: {$_SESSION['user_id']}, job_post_id: 2)</h2>";
ob_start();
$controller->handleRequest(); // default: $_GET['action'] is not set, so let's inject it below
$response = ob_get_clean();
echo "<pre>$response</pre>";

// Cleanup wrapper
stream_wrapper_restore('php');