<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["doctor_id"])) {
    $doctorId = $_POST["doctor_id"];

    // Delete doctor from the database
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctorId);

    if ($stmt->execute()) {
        header("Location: doctors.php?message=Doctor removed successfully!");
    } else {
        header("Location: doctors.php?error=Failed to remove doctor.");
    }

    $stmt->close();
}
?>
