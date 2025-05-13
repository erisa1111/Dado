<?php
require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../App/Controllers/PostsController.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\PostsController;

header('Content-Type: application/json');

$controller = new PostsController();
$posts = $controller->getPosts();

echo json_encode($posts);
