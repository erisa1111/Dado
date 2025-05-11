<?php
// Enable strict error handling
error_reporting(0);
ini_set('display_errors', 0);

// Clean any existing output
while (ob_get_level()) ob_end_clean();

// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Force JSON response
header('Content-Type: application/json');

try {
    // Only handle POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST requests allowed');
    }

    // Get the raw POST data
    $input = file_get_contents('php://input');
    if (empty($input)) {
        throw new Exception('No data received');
    }

    // Decode JSON
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON: ' . json_last_error_msg());
    }

    // Validate required fields
    if (empty($data['action']) || empty($data['user_one_id'])) {
        throw new Exception('Missing required fields');
    }

    // Load dependencies
    require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';
    require_once __DIR__ . '/../../Config/Database.php';

    // Process request
    $controller = new App\Controllers\ConnectionsController();
    $controller->handleConnectionAction();
    
    // Ensure no further output
    exit;

} catch (Exception $e) {
    // Return clean JSON error
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
    exit;
}