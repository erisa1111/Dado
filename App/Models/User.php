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
    
    public function getUserByEmail($email)
{
    try {
        // Prepare the SQL query to get the user by email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);

        // Fetch the user data as an associative array
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null; // Return user if found, otherwise null
    } catch (PDOException $e) {
        throw new Exception("Database error: " . $e->getMessage());
    }
}

}
