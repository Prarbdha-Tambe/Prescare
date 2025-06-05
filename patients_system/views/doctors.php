<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Fetch all doctors from the database
$search = "";
$filter = "";

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT * FROM doctors WHERE name LIKE '%$search%' OR specialization LIKE '%$search%' OR hospital LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM doctors";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctors Directory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Theme Colors */
        :root {
            --primary-color: #63FFB7;
            --text-color: #000000;
            --btn-hover: #50E0A0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-color) !important;
            padding: 15px 20px;
            transition: 0.3s ease-in-out;
            box-shadow: 0 4px 10px var(--shadow-color);
        }

        .navbar-brand {
            font-family: 'League Spartan', sans-serif;
            font-weight: bold;
            font-size: 1.9rem;
            letter-spacing: -1.5px;
            transition: color 0.3s;
        }

        .navbar-brand:hover {
            color: var(--btn-hover) !important;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: var(--btn-hover) !important;
        }

        /* Header */
        .container-header {
            text-align: center;
            margin-top: 40px;
            animation: fadeInUp 0.8s ease-in-out;
        }

        .container-header h2 {
            font-weight: bold;
        }

        /* Search Bar */
        .search-container {
            max-width: 700px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-in-out;
        }

        .search-container input {
            border-radius: 12px;
            padding: 12px;
            font-size: 16px;
            transition: 0.3s;
        }

        .search-container input:focus {
            box-shadow: 0px 0px 10px rgba(99, 255, 183, 0.5);
            border-color: var(--primary-color);
        }

        /* Cards */
        .card {
            border-radius: 12px;
            box-shadow: 0px 5px 12px rgba(0, 0, 0, 0.1);
            transition: 0.3s ease-in-out;
            background: white;
            padding: 20px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
        }

        .card h5 {
            font-weight: bold;
            margin-bottom: 10px;
        }

        /* Buttons */
        .btn-custom {
            background: var(--primary-color);
            color: black;
            border-radius: 12px;
            transition: 0.3s;
            font-weight: bold;
            padding: 10px 20px;
            text-transform: uppercase;
        }

        .btn-custom:hover {
            background: var(--btn-hover);
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(99, 255, 183, 0.5);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-color);
            color: black;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            font-weight: bold;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(50px); }
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

<!-- Page Header -->
<div class="container-header">
    <h2>Doctors Directory</h2>
    <p>Find the right doctor for your needs.</p>
</div>

<!-- Add Doctor Button -->
<div class="container text-center mb-4">
    <a href="add_doctor.php" class="btn btn-custom">+ Add Doctor</a>
</div>

<!-- Search Bar -->
<div class="container search-container mt-4">
    <form class="row g-3" method="GET">
        <div class="col-md-9">
            <input type="text" class="form-control" name="search" placeholder="Search by name, specialization, or hospital" value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-custom w-100">Search</button>
        </div>
    </form>
</div>

<!-- Doctors List -->
<div class="container mt-5">
    <div class="row">
        <?php if ($result->num_rows > 0) { ?>
            <?php while ($doctor = $result->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card p-4">
                        <h5 class="card-title"><?= htmlspecialchars($doctor["name"]) ?></h5>
                        <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor["specialization"]) ?></p>
                        <p><strong>Hospital:</strong> <?= htmlspecialchars($doctor["hospital"]) ?></p>
                        <p><strong>Contact:</strong> <?= htmlspecialchars($doctor["contact"]) ?></p>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="text-center text-muted">No doctors found.</p>
        <?php } ?>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2024 prescare | Your Personal Health Companion</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
