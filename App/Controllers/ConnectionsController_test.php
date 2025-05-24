<?php
require_once __DIR__ . '/../../App/Controllers/ConnectionsController.php';

use App\Controllers\ConnectionsController;

// Initialize the controller
$controller = new ConnectionsController();

echo "<h2>Testing getConnections(92)</h2>";
$result = $controller->getConnections(92); // returns array
echo "<pre>";
print_r($result);
echo "</pre>";

// To test getConnectionsApi, simulate a request like a browser:
echo "<h2>Testing getConnectionsApi (simulated GET)</h2>";
$_GET['user_id'] = 92;

ob_start();
$controller->getConnectionsApi(); // This will echo JSON and call exit
$output = ob_get_clean();

echo "<pre>$output</pre>";