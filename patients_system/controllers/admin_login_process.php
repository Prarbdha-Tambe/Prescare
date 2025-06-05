<?php
session_start();
include "../config/db.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // Check if admin exists
    $query = "SELECT * FROM admins WHERE username='$username' LIMIT 1";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $admin = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $admin["password"])) {
            $_SESSION["admin"] = $admin["id"]; // Set session
            header("Location: ../views/admin_dashboard.php"); // Redirect to dashboard
            exit;
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='../views/admin_login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid username!'); window.location.href='../views/admin_login.php';</script>";
    }
}
?>
