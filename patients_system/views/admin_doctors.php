<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all doctors
$doctors = $conn->query("SELECT * FROM doctors");

// Handle Delete Request
if (isset($_GET["delete"])) {
    $doctorId = $_GET["delete"];
    $conn->query("DELETE FROM doctors WHERE id='$doctorId'");
    header("Location: admin_doctors.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Doctors - prescare</title>
    
    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --hover-color: #F17F29;
            --text-color: #333;
            --sidebar-bg: #f8f9fa;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background-color: var(--sidebar-bg);
            color: var(--text-color);
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background: var(--primary-color);
            padding-top: 20px;
            transition: 0.3s;
            box-shadow: 5px 0px 15px var(--shadow-color);
        }

        .sidebar h4 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: white;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            border-radius: 5px;
            margin: 5px;
        }

        .sidebar a:hover {
            background: var(--hover-color);
            transform: scale(1.05);
        }

        .sidebar .text-danger {
            font-weight: bold;
        }

        /* Content */
        .content {
            margin-left: 260px;
            padding: 20px;
        }

        /* Table */
        .table {
            border-radius: 10px;
            box-shadow: 0px 5px 10px var(--shadow-color);
            overflow: hidden;
            animation: fadeInTable 0.8s ease-in-out;
        }

        .table th {
            background: var(--primary-color);
            color: white;
        }

        /* Buttons */
        .btn-add {
            background: var(--hover-color);
            color: white;
            transition: all 0.3s ease-in-out;
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn-add:hover {
            background: black;
            color: white;
            transform: scale(1.05);
        }

        .btn-danger {
            transition: all 0.3s ease-in-out;
        }

        .btn-danger:hover {
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInTable {
            from { opacity: 0; transform: scale(0.9); }
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
    <a href="admin_doctors.php" class="active">Manage Doctors</a>
    <a href="admin_profile.php">Profile</a>
    <a href="admin_logout.php" class="text-danger">Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">Manage Doctors</h2>

    <!-- Add Doctor Button -->
    <a href="admin_add_doctor.php" class="btn btn-add mb-3">+ Add Doctor</a>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Hospital</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($doctor = $doctors->fetch_assoc()) { ?>
                <tr>
                    <td><?= $doctor["id"] ?></td>
                    <td><?= htmlspecialchars($doctor["name"]) ?></td>
                    <td><?= htmlspecialchars($doctor["specialization"]) ?></td>
                    <td><?= htmlspecialchars($doctor["hospital"]) ?></td>
                    <td><?= htmlspecialchars($doctor["contact"]) ?></td>
                    <td>
                        <a href="admin_doctors.php?delete=<?= $doctor["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
