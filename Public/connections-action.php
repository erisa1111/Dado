<?php
session_start();
require_once __DIR__ . '/App/Controllers/ConnectionsController.php';  

use App\Controllers\ConnectionsController;

$controller = new ConnectionsController();
$controller->handleConnectionAction();
?>