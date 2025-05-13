<?php
require_once __DIR__ . '/Connections.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust based on actual file structure

use App\Models\Connections;

echo "<h1>Testing Connections model</h1>";

$connections = new Connections();

echo "<h2>Testing getPendingRequests(1)</h2>";
$pending = $connections->getPendingRequests(24);
echo "<pre>";
print_r($pending);
echo "</pre>";

echo "<h2>Testing getUserConnections(1)</h2>";
$userConnections = $connections->getUserConnections(24);
echo "<pre>";
print_r($userConnections);
echo "</pre>";

$sender_id = 24;
$receiver_id = 25; // Replace with a valid user ID in your DB

echo "<h2>Testing sendConnectionRequest($sender_id, $receiver_id)</h2>";
$response = $connections->sendConnectionRequest($sender_id, $receiver_id);
echo "<pre>";
print_r($response);
echo "</pre>";


echo "<h2>Testing connectionExists($sender_id, $receiver_id)</h2>";
$connectionExists = $connections->connectionExists($sender_id, $receiver_id);
echo "<pre>";
echo "Connection exists: " . ($connectionExists ? "Yes" : "No");
echo "</pre>";

echo "<h2>Testing getConnectionStatus($sender_id, $receiver_id)</h2>";
$statusResult = $connections->getConnectionStatus($sender_id, $receiver_id);
echo "<pre>";
print_r($statusResult);
echo "</pre>";