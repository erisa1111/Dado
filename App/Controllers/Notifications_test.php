<?php
// NotificationsController_test.php
// A simple test harness for App\Controllers\NotificationsController

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ob_start(); // buffer output so headers can be sent by controller

session_start();

require_once __DIR__ . '/NotificationsController.php';
require_once __DIR__ . '/../../App/Models/Notification.php';
require_once __DIR__ . '/../../Config/Database.php';

use App\Controllers\NotificationsController;

echo "<h1>Testing NotificationsController</h1>";

// Simulate a logged-in user
$_SESSION['user_id'] = 86; // replace with actual user ID having notifications

$controller = new NotificationsController();

// Test JSON fetch()
echo "<h2>Test fetch()</h2>";
ab:
$fetchOutput = null;
ob_start();
$controller->fetch();
$fetchOutput = ob_get_clean();
echo "<pre>" . htmlspecialchars($fetchOutput) . "</pre>";

// Test index() rendering
echo "<h2>Test index()</h2>";
ob_start();
$controller->index(); // this will include the notifications.php view
$viewOutput = ob_get_clean();
echo "<div style='border:1px solid #ccc;padding:10px;'>" . $viewOutput . "</div>";

// // ✅ TEST: createJobFromApplication()
echo "<h2>Test createJobFromApplication()</h2>";
$_POST['application_id'] = 21; // ✅ Replace with a valid application ID from your DB
ob_start();
$controller->createJobFromApplication();
$jobOutput = ob_get_clean();
echo "<pre style='color:blue;'>" . htmlspecialchars($jobOutput) . "</pre>";

// ✅ TEST: acceptAndCreateJob()
echo "<h2>Test acceptAndCreateJob()</h2>";
$_POST['application_id'] = 22; // ✅ Replace with another valid application ID
ob_start();
$controller->acceptAndCreateJob();
$acceptOutput = ob_get_clean();
echo "<pre style='color:green;'>" . htmlspecialchars($acceptOutput) . "</pre>";

// ------------------------------------------------------------


ob_end_flush(); // send all buffered output
