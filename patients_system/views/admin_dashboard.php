<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

// Prevent Back Button Access After Logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Fetch total counts for dashboard stats
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalDoctors = $conn->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc()['count'];
$totalRecords = $conn->query("SELECT COUNT(*) as count FROM medical_records")->fetch_assoc()['count'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>prescare admin dashboard</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --secondary-color: #ffffff;
            --text-color: #333;
            --sidebar-bg: rgba(249, 105, 0, 0.9);
            --btn-hover: #d45a00;
            --shadow-light: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background: linear-gradient(to bottom, #fffaf3, #f8f9fa);
            height: 100vh;
            display: flex;
            animation: fadeInBody 0.6s ease-in-out;
        }

        /* Sidebar */
        .sidebar {
            background: var(--sidebar-bg);
            width: 250px;
            height: 100vh;
            padding: 20px;
            color: white;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease-in-out;
        }

        .sidebar h4 {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: -1.5px;
            text-transform: lowercase;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar a {
            color: white;
            padding: 12px;
            display: block;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            transition: all 0.3s ease-in-out;
        }

        .sidebar a:hover {
            background: var(--btn-hover);
            transform: translateX(5px);
        }

        /* Dashboard Container */
        .dashboard-container {
            flex-grow: 1;
            padding: 40px;
            animation: fadeInUp 0.6s ease-in-out;
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: -1.5px;
            margin-bottom: 30px;
        }

        /* Dashboard Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            animation: fadeInCard 0.8s ease-in-out forwards;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 20px rgba(249, 105, 0, 0.3);
        }

        .card h5 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
        }

        /* Animations */
        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInCard {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php">Manage Users</a>
    <a href="admin_doctors.php">Manage Doctors</a>
    <a href="admin_profile.php">Profile</a>
    <a href="admin_logout.php" class="text-danger">Logout</a>

    <!-- Switch to User Panel Button -->
    <form action="switch_to_user.php" method="POST" class="text-center mt-3">
        <button type="submit" class="btn btn-dark w-75">Switch to User Panel</button>
    </form>
</div>


<!-- Dashboard Content -->
<div class="dashboard-container">
    <h2 class="dashboard-title">Welcome, Admin</h2>

    <!-- Dashboard Statistics -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <h5>Total Users</h5>
                <h3><?= $totalUsers ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <h5>Total Doctors</h5>
                <h3><?= $totalDoctors ?></h3>
            </div>
        </div>
    </div>
</div>

</body>
</html>
