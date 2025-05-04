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
        
        // Use error_log for debugging instead of echo
        error_log("Pending requests for user $user_id: " . print_r($result, true));
        
        return $result;
    }

    public function getUserConnections($user_id)
    {
        $stmt = $this->db->prepare("CALL GetUserConnections(?)");
        $stmt->execute([$user_id]);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        error_log("User connections for $user_id: " . print_r($result, true));
        
        return $result;
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