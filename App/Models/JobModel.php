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
// App/Models/JobModel.php
public function updateJobStatuses() {
    try {

        $this->db->beginTransaction();

        $sql = "UPDATE jobs
                SET status = CASE 
                    WHEN end_date < CURDATE() THEN 'closed'
                    ELSE 'ongoing'
                END";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute();

        $this->db->commit();

        return $result;
    } catch (\PDOException $e) {
        $this->db->rollBack();
        error_log("Failed updating job statuses: " . $e->getMessage());
        echo "Error updating statuses: " . $e->getMessage();
        return false;
    }
}




}

