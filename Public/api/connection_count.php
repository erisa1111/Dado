<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';

use App\Controllers\ConnectionsController;

if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing user_id']);
    exit;
}

$userId = intval($_GET['user_id']);

$controller = new ConnectionsController();

// Use the public method to get count
$count = $controller->getConnectionCount($userId);

echo json_encode([
    'success' => true,
    'user_id' => $userId,
    'connection_count' => $count
]);
exit;