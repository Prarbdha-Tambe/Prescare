<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user"];
$result = $conn->query("SELECT * FROM users WHERE id='$userId'");
$user = $result->fetch_assoc();

// Fetch user's latest 5 medical records
$records = $conn->query("SELECT * FROM medical_records WHERE user_id='$userId' ORDER BY uploaded_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC");

// Calculate BMI (Body Mass Index)
$heightMeters = $user["height"] ? $user["height"] / 100 : 0; // Convert cm to meters
$weightKg = $user["weight"] ?: 0;
$bmi = ($heightMeters > 0 && $weightKg > 0) ? round($weightKg / ($heightMeters * $heightMeters), 1) : "N/A";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Patient Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="medical_records.php">Medical Records</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="../controllers/logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center text-primary">Welcome, <?= $user["username"] ?>!</h2>

        <div class="row mt-4">
            <!-- Health Stats -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-info text-white text-center">
                        <h5>Health Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Height:</strong> <?= $user["height"] ?: "Not Specified" ?> cm</p>
                        <p><strong>Weight:</strong> <?= $user["weight"] ?: "Not Specified" ?> kg</p>
                        <p><strong>BMI:</strong> <?= $bmi ?> (<?= $bmi != "N/A" ? ($bmi < 18.5 ? "Underweight" : ($bmi < 25 ? "Normal" : ($bmi < 30 ? "Overweight" : "Obese"))) : "N/A" ?>)</p>
                        <p><strong>Blood Pressure:</strong> <?= $user["blood_pressure"] ?: "Not Specified" ?></p>
                    </div>
                </div>
            </div>

            <!-- Recent Medical Records -->
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h5>Recent Medical Records</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($records)) { ?>
                            <ul class="list-group">
                                <?php foreach ($records as $record) { ?>
                                    <li class="list-group-item">
                                        <?= $record["category"] ?> - <?= date("M d, Y", strtotime($record["uploaded_at"])) ?>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } else { ?>
                            <p class="text-muted">No medical records uploaded yet.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Access Buttons -->
        <div class="row mt-4">
            <div class="col-md-4">
                <a href="profile.php" class="btn btn-warning w-100">View Profile</a>
            </div>
            <div class="col-md-4">
                <a href="medical_records.php" class="btn btn-primary w-100">Manage Medical Records</a>
            </div>
            <div class="col-md-4">
                <a href="../controllers/logout.php" class="btn btn-danger w-100">Logout</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
