<?php
require_once __DIR__ . '/../../App/Controllers/CommentsController.php';
use App\Controllers\CommentsController;

$controller = new CommentsController();

if ($_GET['action'] == 'updateComment') {
    $controller->updateComment();
}elseif(($_GET['action'] == 'deleteComment')){
    $controller->deleteComment();
}
?>
