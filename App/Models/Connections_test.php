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
