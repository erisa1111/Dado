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
}?>


<!-- public function acceptConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL AcceptConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        // Log for debugging
        error_log("Accept connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }

    public function deleteConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL DeleteConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        // Log for debugging
        error_log("Delete connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }

    public function createConnection($user_one_id, $user_two_id)
    {
        $stmt = $this->db->prepare("CALL CreateConnection(?, ?)");
        $success = $stmt->execute([$user_one_id, $user_two_id]);
        
        // Log for debugging
        error_log("Create connection between $user_one_id and $user_two_id: " . ($success ? "Success" : "Failure"));
        
        return $success;
    }
} -->