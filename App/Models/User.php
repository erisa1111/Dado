<?php

namespace App\Models;

use Config\Database;
use PDO;
use PDOException;
require_once dirname(__DIR__) . '/../Config/Database.php'; // Adjust the path if needed

class User
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect(); // Ensure this returns a PDO instance
    }

    public function createUser($data)
    {
        try {
            $stmt = $this->conn->prepare("CALL CreateUser(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
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



}
