<?php

namespace App\Models;

use Config\Database;
use PDO;
use PDOException;
require_once dirname(__DIR__) . '/../Config/Database.php'; // Adjust the path if needed

class User
{
    public int $lastId;
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect(); // Ensure this returns a PDO instance
    }

    public function createUser($data)
    {
        try {
            //qetu  e bon qe me tu kthy id e userit qe sa u ba signup
            $stmt = $this->conn->prepare("CALL CreateUser(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, @new_id)");
    
            $stmt->execute([
                $data['name'],
                $data['surname'],
                $data['email'],
                $data['username'],
                $data['phone_number'],
                $data['password'],
                $data['location'],
                $data['gender'],
                $data['role_id'], // CHANGE: pass role_id not user_type
                $data['expected_salary'] !== null ? (float)$data['expected_salary'] : null,
                $data['experience'] !== null ? (int)$data['experience'] : null,
                $data['schedule']
            ]);
    
             $row = $this->conn
                ->query("SELECT @new_id AS id")
                ->fetch(PDO::FETCH_ASSOC);

            $this->lastId = isset($row['id']) ? (int)$row['id'] : null;
            return true;
    
        } catch (PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function getUserByEmail($email){
        try {
            // Prepare the SQL query to get the user by email
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);

            // Fetch the user data as an associative array
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? $user : null; // Return user if found, otherwise null
        } catch (PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());

        }
    }


    public function getProfile($userId){
        $stmt = $this->conn->prepare("CALL GetUserProfile(:userId)");
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor(); // Important after a CALL!
        
        return $result;
    }
    public function updateProfile($userId, $data){
        try {
            // Prepare the SQL query
            $query = "UPDATE users SET 
                        username = :username, 
                        name = :name, 
                        surname = :surname, 
                        location = :location, 
                        phone_number = :phone_number, 
                        email = :email, 
                        bio = :bio, 
                        profile_picture = :profile_picture
                      WHERE id = :user_id";

            $stmt = $this->conn->prepare($query);

            // Bind the values to the placeholders
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindParam(':surname', $data['surname'], PDO::PARAM_STR);
            $stmt->bindParam(':location', $data['location'], PDO::PARAM_STR);
            $stmt->bindParam(':phone_number', $data['phone_number'], PDO::PARAM_STR);
            $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindParam(':bio', $data['bio'], PDO::PARAM_STR);
            $stmt->bindParam(':profile_picture', $data['profile_picture'], PDO::PARAM_STR);

            // Execute the query
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            throw new \Exception("Profile update failed: " . $e->getMessage());
        }
    }

  public function isUsernameTaken($username) {
    // Use the already established connection ($this->conn)
    $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    return $stmt->fetchColumn() > 0;
}

public function storeVerificationToken($userId, $token)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
            return $stmt->execute([$token, $userId]);
        } catch (PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get user by verification token
     */
    public function getUserByVerificationToken($token)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE verification_token = ?");
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Verify user by token
     */
    public function verifyUser($token)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET is_verified = TRUE, verification_token = NULL WHERE verification_token = ?");
            return $stmt->execute([$token]);
        } catch (PDOException $e) {
            throw new \Exception("Database error: " . $e->getMessage());
        }
    }

    /**
     * Get the last inserted user ID
     * Needed because your createUser uses a stored procedure
     */
    public function getLastInsertId(): int
    {
        // return the ID captured from the OUT parameter
        return $this->lastId ?? 0;
    }

}