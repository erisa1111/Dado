<?php
namespace App\Models;

use Config\Database;

class Connections
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getPendingRequests($user_id)
{
    $stmt = $this->db->prepare("CALL GetPendingRequests(?)");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    // Log for debugging
    error_log("Pending requests for user $user_id: " . print_r($result, true));
    
    return $result;
}

public function getUserConnections($user_id)
{
    $stmt = $this->db->prepare("CALL GetUserConnections(?)");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Add 'status' as 'accepted' directly here
    foreach ($result as &$conn) {
        $conn['status'] = 'accepted';
    }

    error_log("Accepted connections for user $user_id: " . print_r($result, true));

    return $result;
}
public function getAllConnections($user_id)
{
    $pending = $this->getPendingRequests($user_id);
    $accepted = $this->getUserConnections($user_id);

        // Tag status for each connection (already part of the result)
        foreach ($pending as &$conn) {
            $conn['status'] = 'pending';
        }

        foreach ($accepted as &$conn) {
            $conn['status'] = 'accepted';
        }

        return array_merge($pending, $accepted);
    }

    public function acceptConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL AcceptConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        error_log("Accept connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }

    public function deleteConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL DeleteConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        error_log("Delete connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }

    public function createConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL CreateConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        error_log("Create connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }

    public function sendConnectionRequest($sender_id, $receiver_id)
{
    $stmt = $this->db->prepare("CALL send_connection_request(?, ?)");
    $stmt->execute([$sender_id, $receiver_id]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    error_log("Send connection request from $sender_id to $receiver_id: " . print_r($result, true));

    return $result;
}

public function getConnectionStatus($userId1, $userId2)
{
    $stmt = $this->db->prepare("SELECT * FROM connections WHERE ((user_one_id = ? AND user_two_id = ?) OR (user_one_id = ? AND user_two_id = ?)) AND status IN ('pending', 'accepted')");
    $stmt->execute([$userId1, $userId2, $userId2, $userId1]);
    $result = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($result) {
        if ($result['status'] === 'pending') {
            return ['success' => 0, 'message' => 'You have already sent a connection request, it is pending.'];
        } elseif ($result['status'] === 'accepted') {
            return ['success' => 0, 'message' => 'You are already connected with this user.'];
        }
    }

    return ['success' => 1, 'message' => 'No connection, ready to send request.'];
}
public function connectionExists($userId1, $userId2)
{
    // Correct the stored procedure call to pass the parameters user_id1 and user_id2
    $stmt = $this->db->prepare("CALL CheckConnectionExists(?, ?)");
    $stmt->execute([$userId1, $userId2]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    error_log("Checking if connection exists between $userId1 and $userId2: " . print_r($result, true));

    return count($result) > 0;
}
}
