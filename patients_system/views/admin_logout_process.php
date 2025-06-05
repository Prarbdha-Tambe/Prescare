<?php
session_start();

// Enable error reporting (Temporary for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_destroy(); // Destroy session

// Redirect to login page
header("Location: ../views/admin_login.php");
exit;
?>
