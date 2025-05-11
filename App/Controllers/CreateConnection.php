<?php
session_start();
require_once __DIR__ . '/../../App/Models/Connections.php';

use App\Models\Connections;

$connectionsModel = new Connections();
$user_one_id = $_GET['user_one_id'];
$user_two_id = $_GET['user_two_id'];

$success = $connectionsModel->createConnection($user_one_id, $user_two_id);

echo json_encode(['success' => $success]);