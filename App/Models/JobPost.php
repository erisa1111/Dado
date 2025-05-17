<?php
namespace App\Models;

use Config\Database;
use PDO; // ✅ ADD THIS LINE
use PDOException;

class JobPost{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("CALL GetAllJobPosts()");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("CALL GetJobPostById(?)");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function create($parentId, $title, $description, $location, $salary, $schedule, $numKids, $startHour, $endHour, $dateFrom, $dateTo)
    {
        $stmt = $this->db->prepare("CALL CreateJobPost(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $parentId,
            $title,
            $description,
            $location,
            $salary,
            $schedule,
            $numKids,
            $startHour,
            $endHour,
            $dateFrom,
            $dateTo
        ]);
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result['new_id'] ?? null;
    }

    public function update($id, $title, $description, $location, $salary, $schedule, $numKids, $startHour, $endHour, $dateFrom, $dateTo, $status)
    {
        $stmt = $this->db->prepare("CALL UpdateJobPost(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $id,
            $title,
            $description,
            $location,
            $salary,
            $schedule,
            $numKids,
            $startHour,
            $endHour,
            $dateFrom,
            $dateTo,
            $status
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("CALL DeleteJobPost(?)");
        return $stmt->execute([$id]);
    }

    public function close($id)
    {
        $stmt = $this->db->prepare("CALL CloseJobPost(?)");
        return $stmt->execute([$id]);
    }
    public function hasAlreadyApplied(int $nannyId, int $jobPostId): bool
{
    $stmt = $this->db->prepare("CALL HasAlreadyApplied(?, ?)");
    $stmt->execute([$nannyId, $jobPostId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    
    return $result && $result['already_applied'] == 1;
}
    public function applyToJobPost($nannyId, $jobPostId, $status = 'pending')
{
    $stmt = $this->db->prepare("CALL CreateJobApplication(?, ?, ?)");
    $stmt->execute([
        $nannyId,
        $jobPostId,
        $status
    ]);

    // If you want to confirm it worked:
    $result = $stmt->fetch();
    $stmt->closeCursor();
    return $result ?? true; // return result if any, or just true
}
}

