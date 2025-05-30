<?php
namespace App\Models;

use Config\Database;
use PDO; 
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
public function closeJobPost($jobPostId, $parentId) {
    $stmt = $this->db->prepare("CALL close_job_post(?, ?)");
    $stmt->execute([$jobPostId, $parentId]);
    $stmt->closeCursor();
    return true;
}

public function getJobPostsByUserId($userId) {
    $query = "
        SELECT 
            jp.*,
            u.username,
            u.profile_picture,
            (SELECT COUNT(*) FROM job_post_likes WHERE job_post_id = jp.id) AS job_like_count,
            (SELECT COUNT(*) FROM job_post_comments WHERE job_post_id = jp.id) AS job_comment_count
        FROM job_posts jp
        JOIN users u ON jp.parent_id = u.id
        WHERE jp.parent_id = ?
        ORDER BY jp.created_at DESC;
    ";
    $stmt = $this->db->prepare($query);
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

public function searchJobs($query) {
        $sql = "SELECT 
                    jp.*,
                    u.username,
                    u.profile_picture,
                    (SELECT COUNT(*) FROM job_post_likes WHERE job_post_id = jp.id) AS job_like_count,
                    (SELECT COUNT(*) FROM job_post_comments WHERE job_post_id = jp.id) AS job_comment_count
                FROM job_posts jp
                JOIN users u ON jp.parent_id = u.id
                WHERE jp.title LIKE :query 
                OR jp.description LIKE :query 
                OR jp.schedule LIKE :query 
                OR u.username LIKE :query
                ORDER BY jp.created_at DESC";

        $stmt = $this->db->prepare($sql);

        $likeQuery = '%' . $query . '%';
        $stmt->bindValue(':query', $likeQuery, \PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }



}