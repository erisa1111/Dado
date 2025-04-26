<?php
class Database {
    private $host = "sql7.freesqldatabase.com"; 
    private $db_name = "sql7775438";
    private $username = "sql7775438";
    private $password = "QiwzcdJ5GM";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, 
                                  $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>