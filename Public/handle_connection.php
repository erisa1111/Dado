<?php
error_log("handle_connection.php called");
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
// ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

header('Content-Type: application/json');

require_once __DIR__ . '/../App/Models/Connections.php';
require_once __DIR__ . '/../App/Controllers/ConnectionsController.php';

use App\Controllers\ConnectionsController;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_one_id'], $data['user_two_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

$controller = new ConnectionsController();
$action = $data['action'] ?? 'add';  // default to 'add' if not provided

$controller = new ConnectionsController();

if ($action === 'remove') {
    $controller->handleRemoveConnection($data['user_one_id'], $data['user_two_id']);
} else {
    $controller->handleSendConnectionRequest($data['user_one_id'], $data['user_two_id']);
}
exit;
