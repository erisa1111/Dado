<?php
session_start();
$_SERVER['REQUEST_METHOD'] = 'POST';

require_once __DIR__ . '/ConnectionsController.php';
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\ConnectionsController;

$controller = new ConnectionsController();

echo "<h2>Testing getConnectionCount()</h2>";

ob_start();
$controller->getConnectionCount(90);  // This will echo JSON and exit
$jsonOutput = ob_get_clean();

echo "<pre>$jsonOutput</pre>";