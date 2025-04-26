<?php
require_once __DIR__ . '/../Config/Database.php';

class Post {
    private $conn;
    private $table_name = "posts"; // your table

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllPosts() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
