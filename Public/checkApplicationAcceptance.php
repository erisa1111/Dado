<?php
require_once __DIR__ . '/../App/Controllers/NotificationsController.php';

use App\Controllers\NotificationsController;

$controller = new NotificationsController();
$controller->checkApplicationAcceptance();