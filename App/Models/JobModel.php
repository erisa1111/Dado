<?php
namespace App\Models;

use Config\Database;
use PDO;
use PDOException;

class JobModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

   public function getUserRole($userId) {
    try {
        $stmt = $this->db->prepare("SELECT role_id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null; // user not found

        switch ($row['role_id']) {
            case 0:
                return 'parent';
            case 2:
                return 'nanny';
            default:
                return null; // other roles or unknown
        }
    } catch (PDOException $e) {
        error_log("Error fetching user role: " . $e->getMessage());
        return null;
    }
}

public function getJobsForUser($userId) {
    $role = $this->getUserRole($userId);
    if (!$role) {
        return []; // role not found or unsupported
    }

    try {
        $stmt = $this->db->prepare("CALL GetJobsByUserIdAndRole(?, ?)");
        $stmt->execute([$userId, $role]);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        while ($stmt->nextRowset()) { } // flush extra result sets

        return $jobs;
    } catch (PDOException $e) {
        error_log("Error in getJobsForUser: " . $e->getMessage());
        return [];
    }
}

}

