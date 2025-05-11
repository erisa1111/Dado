<?php
require_once __DIR__ . '/ConnectionsController.php';
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust based on actual file structure

use App\Controllers\ConnectionsController;

echo "<h1>Testing ConnectionsController</h1>";

// Simulating a session user ID (set the user ID for testing purposes)
$_SESSION['user_id'] = 24;

// Create a mock controller instance
$connectionsController = new ConnectionsController();

// Test getConnections method
echo "<h2>Testing getConnections()</h2>";
$conns = $connectionsController->getConnections();
echo "<pre>";
print_r($conns); // Should display 'pending' and 'accepted' connections
echo "</pre>";

// Test getConnectionsApi method (API response)
echo "<h2>Testing getConnectionsApi()</h2>";
ob_start();
$connectionsController->getConnectionsApi();
$output = ob_get_clean(); // Capture the output (JSON response)
echo "<pre>";
print_r(json_decode($output, true)); // Decode the JSON and print it
echo "</pre>";

// Simulate a POST request to handleConnectionAction
echo "<h2>Testing handleConnectionAction (create connection)</h2>";
$_POST = [
    'action' => 'create',
    'user_one_id' => 1,
    'user_two_id' => 2
];

ob_start();
$connectionsController->handleConnectionAction(); // This should call createConnection internally
$output = ob_get_clean(); // Capture the output (empty or redirect)
echo "<pre>";
echo $output;
echo "</pre>";

// Test the "accept" action
echo "<h2>Testing handleConnectionAction (accept connection)</h2>";
$_POST = [
    'action' => 'accept',
    'user_one_id' => 1,
    'user_two_id' => 2
];

ob_start();
$connectionsController->handleConnectionAction(); // This should call acceptConnection internally
$output = ob_get_clean(); // Capture the output (empty or redirect)
echo "<pre>";
echo $output;
echo "</pre>";

// Test the "delete" action
echo "<h2>Testing handleConnectionAction (delete connection)</h2>";
$_POST = [
    'action' => 'delete',
    'user_one_id' => 1,
    'user_two_id' => 2
];

ob_start();
$connectionsController->handleConnectionAction(); // This should call deleteConnection internally
$output = ob_get_clean(); // Capture the output (empty or redirect)
echo "<pre>";
echo $output;
echo "</pre>";
?>
