<?php
namespace App\Models;
require_once dirname(__DIR__) . '/../Config/Database.php'; // Adjust the path if needed

use Config\Database;
use PDO;
use PDOException;

class RatingModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function insertRating($job_id, $reviewer_id, $rating, $comment) {
        try {
            $stmt = $this->db->prepare("CALL InsertRating(:job_id, :reviewer_id, :rating, :comment)");
            $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
            $stmt->bindParam(':reviewer_id', $reviewer_id, PDO::PARAM_INT);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error inserting rating: " . $e->getMessage());
    // TEMPORARY DEBUGGING ONLY
    echo json_encode(['success' => false, 'error' => 'DB Error: ' . $e->getMessage()]);
    exit;
        }
    }

    public function hasUserRatedJob($job_id, $reviewer_id) {
    try {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM ratings WHERE job_id = ? AND reviewer_id = ?");
        $stmt->execute([$job_id, $reviewer_id]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Error checking rating existence: " . $e->getMessage());
        return false;
    }
}

}

