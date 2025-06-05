<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userId = $_SESSION["user"];
$result = $conn->query("SELECT * FROM users WHERE id='$userId'");
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PresCare - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #63FFB7;
            --text-color: #000000;
            --border-color: rgba(0, 0, 0, 0.1);
            --btn-hover: #50E0A0;
            --shadow-light: 0px 4px 10px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0px 6px 15px rgba(0, 0, 0, 0.15);
        }

        body {
            background-color: #FFFFFF;
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-color) !important;
            padding: 15px 20px;
            transition: top 0.4s ease-in-out;
            box-shadow: 0 4px 10px var(--shadow-color);
        }

        .navbar-brand {
            font-family: 'League Spartan', sans-serif;
            font-weight: bold;
            font-size: 1.6rem;
            letter-spacing: -1.5px;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: var(--btn-hover) !important;
        }

        /* Profile Card */
        .profile-card {
            border-radius: 12px;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-light);
            padding: 30px;
            transition: all 0.3s ease-in-out;
        }

        .profile-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-5px);
        }

        .profile-title {
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 10px;
        }

        .profile-info {
            font-size: 16px;
            font-weight: 500;
            padding: 10px 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-info:last-child {
            border-bottom: none;
        }

        .profile-icon {
            font-size: 18px;
            color: var(--primary-color);
            margin-right: 12px;
        }

        /* Buttons */
        .btn-animated {
            background-color: var(--primary-color);
            border: none;
            color: var(--text-color);
            font-weight: bold;
            transition: all 0.3s ease-in-out;
            border-radius: 10px;
            padding: 12px 20px;
            box-shadow: var(--shadow-light);
        }

        .btn-animated:hover {
            background-color: var(--btn-hover);
            transform: scale(1.05);
            text-decoration: none;
            box-shadow: var(--shadow-hover);
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: var(--text-color);
            text-align: center;
            padding: 15px;
            font-weight: bold;
            margin-top: 30px;
            border-radius: 10px;
            box-shadow: var(--shadow-light);
        }
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
 

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">prescare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="medical_history.php">Medical History</a></li>
                <li class="nav-item"><a class="nav-link" href="medical_records.php">Medical Records</a></li>
                <li class="nav-item"><a class="nav-link" href="doctors.php">Doctors Directory</a></li>
                <li class="nav-item"><a class="nav-link" href="charts.php">Health Charts</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>


<!-- Profile Section -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="profile-card">
                <h2 class="profile-title text-center">User Profile</h2>
                <p class="text-muted text-center">Your personal health details</p>

                <div class="profile-info"><i class="fas fa-user profile-icon"></i> <strong>Username:</strong> <?= htmlspecialchars($user["username"]) ?></div>
                <div class="profile-info"><i class="fas fa-calendar-alt profile-icon"></i> <strong>Date of Birth:</strong> <?= htmlspecialchars($user["date_of_birth"]) ?></div>
                <div class="profile-info"><i class="fas fa-venus-mars profile-icon"></i> <strong>Gender:</strong> <?= htmlspecialchars($user["gender"]) ?></div>
                <div class="profile-info"><i class="fas fa-phone profile-icon"></i> <strong>Phone:</strong> <?= htmlspecialchars($user["phone"]) ?></div>
                <div class="profile-info"><i class="fas fa-map-marker-alt profile-icon"></i> <strong>Address:</strong> <?= htmlspecialchars($user["address"]) ?></div>
                <div class="profile-info"><i class="fas fa-user-shield profile-icon"></i> <strong>Emergency Contact:</strong> <?= htmlspecialchars($user["emergency_contact_name"]) ?> (<?= htmlspecialchars($user["emergency_contact_phone"]) ?>)</div>
                <div class="profile-info"><i class="fas fa-ruler-vertical profile-icon"></i> <strong>Height:</strong> <?= htmlspecialchars($user["height"]) ?: "Not Specified" ?> cm</div>
                <div class="profile-info"><i class="fas fa-weight profile-icon"></i> <strong>Weight:</strong> <?= htmlspecialchars($user["weight"]) ?: "Not Specified" ?> kg</div>
                <div class="profile-info"><i class="fas fa-heartbeat profile-icon"></i> <strong>Blood Pressure:</strong> <?= $user["blood_pressure"] ?: "Not Specified" ?></div>
                <div class="profile-info"><i class="fas fa-notes-medical profile-icon"></i> <strong>Existing Conditions:</strong> <?= htmlspecialchars($user["existing_conditions"]) ?: "None" ?></div>
                <div class="profile-info"><i class="fas fa-allergies profile-icon"></i> <strong>Allergies:</strong> <?= htmlspecialchars($user["allergies"]) ?: "None" ?></div>
                <div class="profile-info"><i class="fas fa-file-medical-alt profile-icon"></i> <strong>Medical Conditions:</strong> <?= htmlspecialchars($user["medical_conditions"]) ?: "None" ?></div>

                <div class="text-center mt-4">
                    <a href="edit_profile.php" class="btn-animated">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2024 prescare | Your Personal Health Companion</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
