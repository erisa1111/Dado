<?php
namespace App\Controllers;

class LogoutController
{
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();

        header('Location: /index.php'); // or wherever your login page is
        exit();
    }
}
?>