<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    private $host = "sql7.freesqldatabase.com"; 
    private $db_name = "sql7775438";
    private $username = "sql7775438";
    private $password = "QiwzcdJ5GM";
    public $conn;

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Lidhja me databazen u kry me sukses!";
        } catch (PDOException $e) {
            echo 'Lidhja deshtoi: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
