<?php
require_once __DIR__ . '/Connections.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust path as needed

use App\Models\Connections;

$connections = new Connections();

$UserId = 90; // Replace with a valid user ID from your DB

echo "<h2>Testing getConnectionCount($UserId)</h2>";
$connectionCount = $connections->getConnectionCount($UserId);
echo "<pre>";
echo "Total accepted connections for user $UserId: $connectionCount";
echo "</pre>";
