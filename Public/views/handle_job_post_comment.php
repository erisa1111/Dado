<?php
require_once __DIR__ . '/../../App/Controllers/CommentsJobPostsController.php';
use App\Controllers\CommentsJobPostsController;

$controller = new CommentsJobPostsController();

if ($_GET['action'] == 'updateComment') {
    $controller->updateCommentForJobPost();
}elseif(($_GET['action'] == 'deleteComment')){
    $controller->deleteCommentForJobPost();
}
?>
