<?php
namespace App\Controllers;
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../Config/Database.php';
use App\Models\Connections;
use Config\Database;

class ConnectionsController
{
    private $connectionsModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->connectionsModel = new Connections($db);
    }

    public function getConnections($user_id) {
        return $this->connectionsModel->getAllConnections($user_id);
    }

    public function getConnectionsApi()
    {
        // Ensure no output before this header call
        header('Content-Type: application/json');
        echo json_encode($this->getConnections());
        exit;
    }

    public function getAllUserConnections($user_id)
    {
        $allConnections = $this->connectionsModel->getAllConnections($user_id);

        // Optionally return as JSON if it's for an API
        header('Content-Type: application/json');
        echo json_encode($allConnections);
    }
 public function getConnectionStatus($senderId, $receiverId)
{
    $model = new \App\Models\Connections();
    $statusData = $model->getConnectionStatus($senderId, $receiverId);

    $message = strtolower($statusData['message']);

    if (strpos($message, 'already connected') !== false) {
        return 'connected';
    } elseif (strpos($message, 'pending') !== false) {
        return 'pending';
    } else {
        return 'none';
    }
}

    public function handleConnectionAction()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? null;
        $userOneId = $input['user_one_id'] ?? null;
        $userTwoId = $input['user_two_id'] ?? $userId;
        
        switch ($action) {
            case 'accept':
                $success = $this->connectionsModel->acceptConnection($userOneId, $userTwoId);
                break;
            case 'decline':
                $success = $this->connectionsModel->deleteConnection($userOneId, $userTwoId);
                break;
            case 'create':
                 $response = $this->connectionsModel->sendConnectionRequest($userOneId, $userTwoId);
                 $success = $response['success'] ?? false;
                break;
            case 'remove':
                 $success = $this->connectionsModel->deleteConnection($userOneId, $userTwoId);
                break;    
            default:
                 $response = ['success' => false, 'message' => 'Invalid action'];
        }

        if ($this->isAjaxRequest()) {
            header('Content-Type: application/json');
           echo json_encode([
                'success' => $success,
                'message' => $response['message'] ?? 'Unknown result'
            ]);
            exit;
        } else {
            header('Location: /connections');
            exit;
        }
    }
}
public function handleRemoveConnection($userOneId, $userTwoId) {
    error_log("handleRemoveConnection called");

    if ($userOneId === $userTwoId) {
        echo json_encode(['success' => false, 'message' => 'You cannot remove yourself']);
        return;
    }

    $connectionModel = $this->connectionsModel;

    if (!$connectionModel->connectionExists($userOneId, $userTwoId)) {
        echo json_encode(['success' => false, 'message' => 'Connection does not exist']);
        return;
    }

    $result = $connectionModel->deleteConnection($userOneId, $userTwoId);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Connection removed successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to remove connection']);
    }
}

public function handleSendConnectionRequest() {
    error_log("handleSendConnectionRequest called");
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['user_one_id'], $data['user_two_id'])) {
        echo json_encode(['success' => false, 'message' => 'Missing required data']);
        return;
    }

    $userOneId = $data['user_one_id'];
    $userTwoId = $data['user_two_id'];

    if ($userOneId === $userTwoId) {
        echo json_encode(['success' => false, 'message' => 'You cannot connect with yourself']);
        return;
    }

    $connectionModel = $this->connectionsModel;

    error_log("userOneId: $userOneId | userTwoId: $userTwoId");

    if ($connectionModel->connectionExists($userOneId, $userTwoId)) {
        echo json_encode(['success' => false, 'message' => 'You are already connected.']);
        return;
    }

    $result = $connectionModel->sendConnectionRequest($userOneId, $userTwoId);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Connection request sent']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send connection request']);
    }
}
public function getConnectionCount($user_id)
{
    return $this->connectionsModel->getConnectionCount($user_id);
}

// Method 2: outputs JSON and exits (useful for direct controller response)
public function outputConnectionCountJson($user_id)
{
    $count = $this->getConnectionCount($user_id);

    if (php_sapi_name() !== 'cli') {
        header('Content-Type: application/json');
    }

    echo json_encode(['user_id' => $user_id, 'connection_count' => $count]);
    exit;
}
    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}

