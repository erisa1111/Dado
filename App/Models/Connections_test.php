<?php
require_once __DIR__ . '/Connections.php';
require_once __DIR__ . '/../../Config/Database.php'; // Adjust path if necessary
use App\Models\Connections;
$connections = new Connections();
$UserId = 92; // Replace with a valid user ID from your DB
// Test getConnectionCount

$userId = 92;

echo "<h2>Testing getUserProfilePicture($userId)</h2>";

// Call the method
$profilePicture = $connections->getUserProfilePicture($userId);

// Show result
echo "<pre>";
if ($profilePicture !== null) {
    echo "Profile picture path for user $userId: $profilePicture";
} else {
    echo "No profile picture found for user $userId or an error occurred.";
}
echo "</pre>";
echo "<h2>Testing getConnectionCount($UserId)</h2>";
$connectionCount = $connections->getConnectionCount($UserId);
echo "<pre>Total accepted connections for user $UserId: $connectionCount</pre>";
// Test getPendingRequests
echo "<h2>Testing getPendingRequests($UserId)</h2>";
$pendingRequests = $connections->getPendingRequests($UserId);
echo "<pre>";
print_r($pendingRequests);
echo "</pre>";
// Test getUserConnections
echo "<h2>Testing getUserConnections($UserId)</h2>";
$userConnections = $connections->getUserConnections($UserId);
echo "<pre>";
print_r($userConnections);
echo "</pre>";
// Test getAllConnections
echo "<h2>Testing getAllConnections($UserId)</h2>";
$allConnections = $connections->getAllConnections($UserId);
echo "<pre>";
print_r($allConnections);
echo "</pre>";