<?php
session_start();
require_once __DIR__ . '/../App/Controllers/NotificationsController.php';

use App\Controllers\NotificationsController;

$controller = new NotificationsController();

if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $controller->fetch();
} else {
    $controller->index();
}