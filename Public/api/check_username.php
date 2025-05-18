<?php
require_once __DIR__ . '/../../App/Controllers/AuthController.php';

use App\Controllers\AuthController;

header('Content-Type: application/json');

$controller = new AuthController();
$controller->checkUsername();