<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 require_once __DIR__ . '/../../App/Controllers/JobPostController.php';// adjust path accordingly

use App\Controllers\JobPostController;

$controller = new JobPostController();
$controller->handleRequest();