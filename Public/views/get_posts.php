<?php
require_once '/Users/macair/Desktop/dadodado/App/Models/Post.php';
require_once '/Users/macair/Desktop/dadodado/App/Controllers/PostsController.php';
require_once '/Users/macair/Desktop/dadodado/Config/Database.php';

use App\Controllers\PostsController;

header('Content-Type: application/json');

$controller = new PostsController();
$posts = $controller->getPosts();

echo json_encode($posts);
