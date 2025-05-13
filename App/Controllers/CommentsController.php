<?php
namespace App\Controllers;

use Exception;
use App\Models\Comment;

require_once __DIR__ . '/../../App/Models/Comment.php';
require_once __DIR__ . '/../../Config/Database.php';

class CommentsController
{
    private $commentModel;
   

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

        $this->commentModel = new Comment();
    }

    public function handleRequest($postId)
    {
        $action = $_GET['action'] ?? '';
        
        switch ($action) {
            case 'getComments':
                $this->getCommentsForPost($postId);
                break;
            case 'addComment':
                $this->addComment();
                break;
            case 'deleteComment':
                $this->deleteComment();
                break;
            case 'updateComment':
                $this->updateComment();
                break;
            default:
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Action not found']);
        }
    }

public function getCommentsForPost($postId)  // Accept $postId as a parameter
{
    header('Content-Type: application/json');
    
    try {
        if (!$postId) {
            throw new Exception('Post ID is required');
        }

        // Get comments from the model
        $comments = $this->commentModel->getCommentsForPost($postId);
        
        // If no comments are found, return empty array
        $comments = $comments ?: [];

        // Output the response
        echo json_encode([
            'success' => true,
            'comments' => $comments
        ]);
    } catch (Exception $e) {
        http_response_code(400);  // Bad request
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}





    public function addComment()
    {
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $postId = $data['post_id'] ?? null;
            $commentText = $data['comment'] ?? '';
            $parentCommentId = $data['parent_comment_id'] ?? null;
            
            $userId = $_SESSION['user_id'] ?? null;
            $username = $_SESSION['username'] ?? 'Anonymous';

            if (!$postId || !$commentText || !$userId) {
                throw new Exception('Invalid data');
            }

            $commentId = $this->commentModel->addComment($postId, $userId, $commentText, $parentCommentId);
            $comment = $this->commentModel->getCommentById($commentId);
            $commentCount = $this->commentModel->getCommentCount($postId);

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

    public function deleteComment()
    {
        header('Content-Type: application/json');
        
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $commentId = $data['comment_id'] ?? null;
            $userId = $_SESSION['user_id'] ?? null;

            if (!$commentId || !$userId) {
                throw new Exception('Invalid data');
            }

            $success = $this->commentModel->deleteComment($commentId, $userId);
            
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

    public function updateComment()
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

            $success = $this->commentModel->updateComment($commentId, $userId, $newComment);
            $comment = $success ? $this->commentModel->getCommentById($commentId) : null;
            
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
