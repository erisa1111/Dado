<?php
require_once __DIR__ . '/../../App/Models/Post.php';
require_once __DIR__ . '/../../App/Controllers/JobPostController.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\JobPostController;

header('Content-Type: application/json');

$controller = new JobPostController();
$posts = $controller->getJobPosts();

echo json_encode($posts);
