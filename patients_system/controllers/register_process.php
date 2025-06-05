<?php
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);
    $email = $_POST["email"];
    $bloodgroup = $_POST["bloodgroup"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];

    $sql = "INSERT INTO users (username, password, email, bloodgroup, height, weight) 
            VALUES ('$username', '$password', '$email', '$bloodgroup', '$height', '$weight')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful. <a href='../views/login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
