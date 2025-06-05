<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

$adminId = $_SESSION["admin"];
$result = $conn->query("SELECT * FROM admins WHERE id='$adminId'");
$admin = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Profile - prescare</title>

    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --hover-color: #F17F29;
            --text-color: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background-color: #f8f9fa;
            animation: fadeIn 0.6s ease-in-out;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
        }

        .card {
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0px 5px 12px var(--shadow-color);
            animation: fadeInUp 0.8s ease-in-out;
        }

        .profile-label {
            font-weight: bold;
            color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: var(--hover-color);
            transform: scale(1.05);
        }

        .btn-secondary:hover {
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Admin Profile</h2>
    <div class="card">
        <p><span class="profile-label">Admin ID:</span> <?= htmlspecialchars($admin["id"]) ?></p>
        <p><span class="profile-label">Username:</span> <?= htmlspecialchars($admin["username"]) ?></p>
        <p><span class="profile-label">Email:</span> <?= htmlspecialchars($admin["email"]) ?></p>
        <p><span class="profile-label">Role:</span> Super Admin</p>
    </div>

    <div class="text-center mt-4">
        <a href="edit_admin_profile.php" class="btn btn-primary">Edit Profile</a>
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
