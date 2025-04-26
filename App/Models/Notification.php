<?php
require_once __DIR__ . '/../Config/Database.php';

class Notification {
    private $conn;
    private $table_name = "notifications"; // your table

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getNotificationsByUserId($userId) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userId);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
