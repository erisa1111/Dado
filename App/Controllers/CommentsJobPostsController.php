<?php
namespace App\Controllers;

use Exception;
use App\Models\CommentJobPost;

require_once __DIR__ . '/../../App/Models/CommentJobPost.php';
require_once __DIR__ . '/../../Config/Database.php';

class CommentsJobPostsController
{
    private $commentModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->commentModel = new CommentJobPost();
    }

    public function handleRequest($jobPostId)
    {
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'getComments':
                $this->getCommentsForJobPost($jobPostId);
                break;
            case 'addComment':
                $this->addCommentForJobPost();
                break;
            case 'deleteComment':
                $this->deleteCommentForJobPost();
                break;
            case 'updateComment':
                $this->updateCommentForJobPost();
                break;
            default:
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Action not found']);
        }
    }

    public function getCommentsForJobPost($jobPostId)
    {
        header('Content-Type: application/json');

        try {
            if (!$jobPostId) {
                throw new Exception('Job Post ID is required');
            }

            $comments = $this->commentModel->getCommentsForJobPost($jobPostId);

            echo json_encode([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addCommentForJobPost()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $jobPostId = $data['job_post_id'] ?? null;
            $commentText = $data['comment'] ?? '';
            $parentCommentId = $data['parent_comment_id'] ?? null;

            $userId = $_SESSION['user_id'] ?? null;
            $username = $_SESSION['username'] ?? 'Anonymous';

            if (!$jobPostId || !$commentText || !$userId) {
                throw new Exception('Invalid data');
            }

            $commentId = $this->commentModel->addCommentForJobPost($jobPostId, $userId, $commentText, $parentCommentId);
            $comment = $this->commentModel->getCommentByIdForJobPost($commentId);
            $commentCount = $this->commentModel->getCommentCountForJobPost($jobPostId);

            echo json_encode([
                'success' => true,
                'comment' => $comment,
                'comment_count' => $commentCount,
                'username' => $username
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteCommentForJobPost()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $commentId = $data['comment_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;

            if (!$commentId || !$userId) {
                throw new Exception('Invalid data');
            }

            $success = $this->commentModel->deleteCommentForJobPost($commentId, $userId);

            echo json_encode([
                'success' => $success,
                'message' => $success ? 'Comment deleted' : 'Failed to delete comment'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateCommentForJobPost()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $commentId = $data['comment_id'] ?? null;
            $newComment = $data['new_comment'] ?? '';
            $userId = $_SESSION['user_id'] ?? null;

            if (!$commentId || !$newComment || !$userId) {
                throw new Exception('Invalid data');
            }

            $success = $this->commentModel->updateCommentForJobPost($commentId, $userId, $newComment);
            $comment = $success ? $this->commentModel->getCommentByIdForJobPost($commentId) : null;

            echo json_encode([
                'success' => $success,
                'comment' => $comment,
                'message' => $success ? 'Comment updated' : 'Failed to update comment'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
