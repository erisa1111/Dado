<?php
namespace App\Controllers;

use Exception;

require_once __DIR__ . '/../../App/Models/JobPost.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Models\JobPost;
use Config\Database;

class JobPostController
{
    private $jobPostModel;
    private $db;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $database = new Database();
        $this->db = $database->connect();
        $this->jobPostModel = new JobPost();
    }

    public function getJobPosts()
    {
        return $this->jobPostModel->getAll();
    }

    public function getJobById($id)
    {
        return $this->jobPostModel->getById($id);
    }

    public function createJobPost($data)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Invalid request method.";
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            throw new Exception('User not logged in.');
        }

        // Validate and sanitize $data as needed before inserting
        $id = $this->jobPostModel->create(
            $userId,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['location'] ?? null,
            $data['salary'] ?? null,
            $data['schedule'] ?? 'part-time',
            $data['num_kids'] ?? null,
            $data['start_hour'] ?? null,
            $data['end_hour'] ?? null,
            $data['date_from'] ?? null,
            $data['date_to'] ?? null
        );

        return $id;
    }
    public function applyToJobPost($data)
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        exit;
    }

    $nannyId = $_SESSION['user_id'] ?? null;
    $jobPostId = $data['job_post_id'] ?? null;
    if (!$nannyId) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'User not logged in.']);
        exit;
    }

   

    if (!$jobPostId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Job post ID is required.']);
        exit;
    }

    
    try {
        $result = $this->jobPostModel->applyToJobPost($nannyId, $jobPostId, 'pending');

        echo json_encode([
            'success' => true,
            'message' => 'Application submitted successfully.',
            'result' => $result
        ]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to submit application: ' . $e->getMessage()
        ]);
    }
}

    public function updateJob($id, $data)
    {
        // You may want to check if user owns the job post or has rights

        return $this->jobPostModel->update(
            $id,
            $data['title'] ?? null,
            $data['description'] ?? null,
            $data['location'] ?? null,
            $data['salary'] ?? null,
            $data['schedule'] ?? 'part-time',
            $data['num_kids'] ?? null,
            $data['start_hour'] ?? null,
            $data['end_hour'] ?? null,
            $data['date_from'] ?? null,
            $data['date_to'] ?? null,
            $data['status'] ?? 'open'
        );
    }

    public function deleteJob($id)
    {
        return $this->jobPostModel->delete($id);
    }

    public function closeJob($id)
    {
        return $this->jobPostModel->close($id);
    }

    public function handleRequest()
    {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'getJobById':
                $id = $_GET['id'] ?? null;
                if ($id) {
                    header('Content-Type: application/json');
                    echo json_encode($this->getJobById($id));
                } else {
                    http_response_code(400);
                    echo json_encode(['success' => false, 'message' => 'Job ID required']);
                }
                break;

            case 'applyToJobPost':
                $postData = json_decode(file_get_contents('php://input'), true);
                $this->applyToJobPost($postData);
            break;
            case 'checkIfApplied':
               $input = json_decode(file_get_contents('php://input'), true);
               $jobPostId = $input['job_post_id'] ?? null;
               $nannyId = $_SESSION['user_id'] ?? null;

                 if (!$nannyId || !$jobPostId) {
                 echo json_encode(['success' => false, 'message' => 'Missing data']);
                 exit;
             }

             $alreadyApplied = $this->jobPostModel->hasAlreadyApplied($nannyId, $jobPostId);
             echo json_encode(['success' => true, 'already_applied' => $alreadyApplied]);
              break;


            // You can add more actions like search, filter etc.

            default:
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Action not found']);
        }
    }
    public function toggleJobLike($postId)
{
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        $postId = $input['job_post_id'] ?? null;
        
        $userId = $_SESSION['user_id'] ?? null;

        if (!$postId || !$userId) {
            return json_encode([
                'success' => false,
                'message' => 'Invalid data'
            ]);
        }

        // Your existing like logic here...
        $isLiked = $this->isJobPostLiked($postId, $userId);

        if ($isLiked) {
            $stmt = $this->db->prepare("DELETE FROM job_post_likes WHERE job_post_id = ? AND user_id = ?");
            $stmt->execute([$postId, $userId]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO job_post_likes (job_post_id, user_id) VALUES (?, ?)");
            $stmt->execute([$postId, $userId]);
        }

        $likeCount = $this->getJobLikeCount($postId);

        return json_encode([
            'success' => true,
            'job_like_count' => $likeCount,
            'is_liked' => !$isLiked
        ]);

    } catch (Exception $e) {
        return json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

    private function isJobPostLiked($postId, $userId)
    {
        // You might need to implement this method in your Post model
        // or use a direct query here
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT 1 FROM job_post_likes WHERE job_post_id = ? AND user_id = ?");
        $stmt->execute([$postId, $userId]);
        return (bool)$stmt->fetch();
    }

    private function getJobLikeCount($postId)
    {
        $database = new Database();
        $db = $database->connect();
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM job_post_likes WHERE job_post_id = ?");
        $stmt->execute([$postId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
