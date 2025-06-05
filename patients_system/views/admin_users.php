<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");

// Handle Delete Request
if (isset($_GET["delete"])) {
    $userId = $_GET["delete"];
    $conn->query("DELETE FROM users WHERE id='$userId'");
    header("Location: admin_users.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage Users - prescare</title>
    
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
        }

        .table th {
            background: var(--primary-color);
            color: white;
        }

        /* Buttons */
        .btn-details {
            background: var(--hover-color);
            color: white;
            transition: all 0.3s ease-in-out;
        }

        .btn-details:hover {
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

        /* Modal */
        .modal-content {
            border-radius: 10px;
            box-shadow: 0px 5px 15px var(--shadow-color);
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4>Admin Panel</h4>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_users.php" class="active">Manage Users</a>
    <a href="admin_doctors.php">Manage Doctors</a>
    <a href="admin_profile.php">Profile</a>
    <a href="admin_logout.php" class="text-danger">Logout</a>
</div>

<!-- Content -->
<div class="content">
    <h2 class="mb-4">Manage Users</h2>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()) { ?>
                <tr>
                    <td><?= $user["id"] ?></td>
                    <td><?= htmlspecialchars($user["username"]) ?></td>
                    <td><?= htmlspecialchars($user["email"]) ?></td>
                    <td><?= htmlspecialchars($user["phone"]) ?></td>
                    <td>
                        <button class="btn btn-details btn-sm" data-bs-toggle="modal" data-bs-target="#userModal<?= $user["id"] ?>">View</button>
                        <a href="admin_users.php?delete=<?= $user["id"] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>

                <!-- User Details Modal -->
                <div class="modal fade" id="userModal<?= $user["id"] ?>" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">User Details - <?= htmlspecialchars($user["username"]) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>
                                <p><strong>Phone:</strong> <?= htmlspecialchars($user["phone"]) ?></p>
                                <p><strong>Address:</strong> <?= htmlspecialchars($user["address"]) ?></p>
                                <p><strong>Emergency Contact:</strong> <?= htmlspecialchars($user["emergency_contact_name"]) ?> (<?= htmlspecialchars($user["emergency_contact_phone"]) ?>)</p>
                                <p><strong>Blood Pressure:</strong> <?= $user["blood_pressure"] ?: "Not Specified" ?></p>
                                <p><strong>Height:</strong> <?= $user["height"] ?: "Not Specified" ?> cm</p>
                                <p><strong>Weight:</strong> <?= $user["weight"] ?: "Not Specified" ?> kg</p>
                                <p><strong>BMI:</strong> <?= ($user["height"] && $user["weight"]) ? round($user["weight"] / (($user["height"] / 100) ** 2), 1) : "N/A" ?></p>
                                <p><strong>Existing Conditions:</strong> <?= htmlspecialchars($user["existing_conditions"]) ?: "None" ?></p>
                                <p><strong>Allergies:</strong> <?= htmlspecialchars($user["allergies"]) ?: "None" ?></p>
                                <p><strong>Medical Conditions:</strong> <?= htmlspecialchars($user["medical_conditions"]) ?: "None" ?></p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
