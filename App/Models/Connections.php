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
    public function getUserProfilePicture(int $userId): ?string
{
    try {
        $stmt = $this->db->prepare("CALL GetUserProfilePicture(?)");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        // Clear any additional result sets to avoid issues with subsequent calls
        while ($stmt->nextRowset()) {}
        return $result['profile_picture'] ?? null;
    } catch (\PDOException $e) {
        error_log("Error fetching profile picture: " . $e->getMessage());
        return null;
    }
}

   public function getPendingRequests($user_id)
{
    $stmt = $this->db->prepare("CALL GetPendingRequests(?)");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    while ($stmt->nextRowset()) {} // Clear any extra result sets

    foreach ($result as &$conn) {
        if (isset($conn['user_one_id'])) {
            $pic = $this->getUserProfilePicture((int)$conn['user_one_id']);
            error_log("Raw profile picture path for user {$conn['user_one_id']}: " . print_r($pic, true));
            if ($pic) {
                $conn['profile_picture'] = '/' . ltrim($pic, '/\\');
            } else {
                $conn['profile_picture'] = '/assets/img/dado_profile.webp';
            }
        } else {
            $conn['profile_picture'] = '/assets/img/dado_profile.webp';
        }
        $conn['status'] = 'pending';
    }

    error_log("Pending requests for user $user_id: " . print_r($result, true));
    
    return $result;
}
public function getUserConnections($user_id)
{
    $stmt = $this->db->prepare("CALL GetUserConnections(?)");
    $stmt->execute([$user_id]);
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    while ($stmt->nextRowset()) {} // clear extra result sets from CALL

    foreach ($result as &$conn) {
        $conn['status'] = 'accepted';

        if (isset($conn['connected_user_id'])) {
            $pic = $this->getUserProfilePicture((int)$conn['connected_user_id']);
            // error_log("Raw profile picture path for user {$conn['connected_user_id']}: " . print_r($pic, true));

            if ($pic) {
                $conn['profile_picture'] = '/' . ltrim($pic, '/\\');
            } else {
                $conn['profile_picture'] = '/assets/img/dado_profile.webp';
            }
        } else {
            $conn['profile_picture'] = '/assets/img/dado_profile.webp';
        }
    }

    error_log("Accepted connections for user $user_id: " . print_r($result, true));
    return $result;
}
public function getAllConnections($user_id)
{
    // These methods already add profile_picture and status
    $pending = $this->getPendingRequests($user_id);
    $accepted = $this->getUserConnections($user_id);
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

public function getConnectionCount($user_id)
{
    try {
        $stmt = $this->db->prepare("CALL GetConnectionCount(?)");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        // Commented out to avoid printing to browser
        // error_log("Connection count for user $user_id: " . print_r($result, true));

        return $result['connection_count'] ?? 0;
    } catch (\PDOException $e) {
        error_log("Error in getConnectionCount for user $user_id: " . $e->getMessage());
        return 0;
    }
}
}
