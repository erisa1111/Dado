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

    public function getConnections()
    {
        $userId = $_SESSION['user_id'];
        return [
            'pending' => $this->connectionsModel->getPendingRequests($userId),
            'accepted' => $this->connectionsModel->getUserConnections($userId)
        ];
    }

    public function getConnectionsApi()
    {
        // Ensure no output before this header call
        header('Content-Type: application/json');
        echo json_encode($this->getConnections());
        exit;
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
            $action = $_POST['action'] ?? null;
            $userOneId = $_POST['user_one_id'] ?? null;
            $userTwoId = $_POST['user_two_id'] ?? $userId;
            switch ($action) {
                case 'accept':
                    $success = $this->connectionsModel->acceptConnection($userOneId, $userTwoId);
                    break;
                case 'delete':
                    $success = $this->connectionsModel->deleteConnection($userOneId, $userTwoId);
                    break;
                case 'create':
                    $success = $this->connectionsModel->createConnection($userOneId, $userTwoId);
                    break;
                default:
                    $success = false;
            }

            // Handle the AJAX request here
            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode(['success' => $success]);
                exit;
            } else {
                // For non-AJAX requests, perform a redirect
                header('Location: /connections');
                exit;
            }
        }
    }

    private function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
}
?>
