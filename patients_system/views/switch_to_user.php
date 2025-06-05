<?php
session_start();

// Log out the admin
unset($_SESSION["admin"]);

// Redirect to user login page
header("Location: login.php");
exit;
?>
