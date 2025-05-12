<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';

use App\Controllers\ConnectionsController;

$senderId = $_GET['sender_id'] ?? null;
$receiverId = $_GET['receiver_id'] ?? null;

if (!$senderId || !$receiverId) {
    echo json_encode(['success' => 0, 'message' => 'Missing parameters']);
    exit;
}

$controller = new ConnectionsController();
$status = $controller->getConnectionStatus($senderId, $receiverId); // <- this must return 'connected', 'pending', or 'none'

echo json_encode([
    'success' => 1,
    'status' => $status
]);
exit;