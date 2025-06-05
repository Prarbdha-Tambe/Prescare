<?php
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_unset();
session_destroy();

// Destroy the session cookie
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), "", time() - 3600, "/");
}

// Prevent back button from accessing a logged-out session
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login page
header("Location: ../views/login.php");
exit;
?>
