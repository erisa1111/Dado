<?php
require_once __DIR__ . '/../Config/Database.php';

class User {
    private $conn;
    private $table_name = "users"; // your user table

    public function createUser($role_id, $name, $surname, $email, $phone_number, $password_hash, $location){
        $db = new \App\Config\Database();
        $conn = $db->getConnection();

        $stmt = $conn->prepare("CALL signup_user(?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("issssss", $role_id, $name, $surname, $email, $phone_number, $password_hash, $location);

        $stmt->execute();
    }


    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Get a user by email (for login)
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get a user by ID (for profile view)
    public function getUserById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user profile (for profile edit)
    public function updateUserProfile($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, email = :email, bio = :bio 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        // Assuming $data is an associative array with name, email, bio keys
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':bio', $data['bio']);
        $stmt->bindParam(':id', $id);

        return $stmt->execute(); // returns true if success
    }
}
?>
