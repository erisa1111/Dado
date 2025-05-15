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

            // You can add more actions like search, filter etc.

            default:
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Action not found']);
        }
    }
}
