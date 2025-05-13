<?php
session_start();
require_once __DIR__ . '/ConnectionsController.php';
require_once __DIR__ . '/../../App/Models/Connections.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust based on actual file structure

use App\Controllers\ConnectionsController;

echo "<h1>Testing handleConnectionAction()</h1>";

// Start a session to simulate a logged-in user

$_SESSION['user_id'] = 24; // Example user ID for testing

// Create a mock controller instance
$connectionsController = new ConnectionsController();

// Testing the 'create' connection action (sending a connection request)
echo "<h2>Testing create connection</h2>";
$_POST = [
    'action' => 'create',
    'user_one_id' => 24, // current user
    'user_two_id' => 25  // user to send request to
];

ob_start();
$connectionsController->handleConnectionAction(); // This will send the connection request
$output = ob_get_clean(); // Capture the output (could be empty or a redirect)
echo "<pre>$output</pre>"; // Output should show success or a redirect (no output expected for redirect)


// Testing the 'accept' connection action (accepting a connection request)
echo "<h2>Testing accept connection</h2>";
$_POST = [
    'action' => 'accept',
    'user_one_id' => 24, // current user
    'user_two_id' => 25  // user whose request to accept
];

ob_start();
$connectionsController->handleConnectionAction(); // This will accept the connection
$output = ob_get_clean(); // Capture the output (could be empty or a redirect)
echo "<pre>$output</pre>"; // Output should show success or a redirect


// Testing the 'delete' connection action (deleting a connection)
echo "<h2>Testing delete connection</h2>";
$_POST = [
    'action' => 'delete',
    'user_one_id' => 24, // current user
    'user_two_id' => 25  // user to delete connection with
];

ob_start();
$connectionsController->handleConnectionAction(); // This will delete the connection
$output = ob_get_clean(); // Capture the output (could be empty or a redirect)
echo "<pre>$output</pre>"; // Output should show success or a redirect
?>