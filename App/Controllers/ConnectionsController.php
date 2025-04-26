<?php
require_once __DIR__ . '/../Models/Connection.php';

class ConnectionsController {

    public function showConnections($userId) {
        $connectionModel = new Connection();
        $connections = $connectionModel->getConnectionsByUserId($userId);

        include '../Views/connections.php';
    }
}
?>
