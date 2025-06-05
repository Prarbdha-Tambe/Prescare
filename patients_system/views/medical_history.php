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

// Handle new entry
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_date = $_POST["event_date"];
    $event_type = $_POST["event_type"];
    $doctor_name = $_POST["doctor_name"];
    $hospital_name = $_POST["hospital_name"];
    $bill_amount = $_POST["bill_amount"];
    $notes = $_POST["notes"];

    $stmt = $conn->prepare("INSERT INTO medical_history (user_id, event_date, event_type, doctor_name, hospital_name, bill_amount, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssds", $userId, $event_date, $event_type, $doctor_name, $hospital_name, $bill_amount, $notes);

    if ($stmt->execute()) {
        header("Location: medical_history.php");
        exit;
    } else {
        $message = "Error adding entry!";
    }
    $stmt->close();
}

// Fetch medical history sorted by year
$result = $conn->query("SELECT * FROM medical_history WHERE user_id='$userId' ORDER BY event_date DESC");
$history = [];
while ($row = $result->fetch_assoc()) {
    $year = date("Y", strtotime($row["event_date"]));
    $history[$year][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            background-color: #f8f9fa;
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: var(--primary-color) !important;
            box-shadow: var(--shadow-light);
        }

        .navbar-brand {
            font-family: 'League Spartan', sans-serif;
            font-weight: bold;
            font-size: 1.9rem;
            letter-spacing: -1.5px;
            transition: color 0.3s;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease-in-out;
        }

        .nav-link:hover {
            color: var(--btn-hover) !important;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            padding: 40px 0;
            font-weight: bold;
        }

        /* Form Card */
        .form-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--shadow-light);
        }

        .form-card h5 {
            font-weight: 600;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px;
            font-size: 15px;
        }

        .btn-custom {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: bold;
        }

        .btn-custom:hover {
            background: var(--btn-hover);
            transform: scale(1.05);
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 25px;
            margin-top: 30px;
        }

        .timeline::before {
            content: "";
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary-color);
            border-radius: 5px;
        }

        .timeline-item {
            position: relative;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: var(--shadow-light);
            margin-bottom: 20px;
            transition: all 0.3s ease-in-out;
        }

        .timeline-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 15px;
            width: 14px;
            height: 14px;
            background: var(--primary-color);
            border-radius: 50%;
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
<div class="page-header">
    <h2>Your Medical History</h2>
    <p>Track your past medical events and expenses.</p>
</div>

<!-- Add Medical History Entry -->
<div class="container">
    <div class="form-card">
        <h5>Add Medical Event</h5>
        <form method="POST">
            <div class="mb-3"><label class="form-label">Event Date</label>
                <input type="date" class="form-control" name="event_date" required>
            </div>
            <div class="mb-3"><label class="form-label">Event Type</label>
                <select class="form-control" name="event_type" required>
                    <option value="Consultation">Consultation</option>
                    <option value="Surgery">Surgery</option>
                    <option value="Treatment">Treatment</option>
                    <option value="Checkup">Checkup</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Doctor Name</label>
                <input type="text" class="form-control" name="doctor_name">
            </div>
            <div class="mb-3"><label class="form-label">Hospital Name</label>
                <input type="text" class="form-control" name="hospital_name">
            </div>
            <div class="mb-3"><label class="form-label">Bill Amount (₹)</label>
                <input type="number" class="form-control" name="bill_amount">
            </div>
            <div class="mb-3"><label class="form-label">Notes</label>
                <textarea class="form-control" name="notes"></textarea>
            </div>
            <button type="submit" class="btn btn-custom w-100">Add Entry</button>
        </form>
    </div>
</div>

<!-- Medical Timeline -->
<div class="container timeline">
    <?php foreach ($history as $year => $events) { ?>
        <h5 class="mt-4"><?= $year ?></h5>
        <?php foreach ($events as $event) { ?>
            <div class="timeline-item">
                <strong><?= date("M d, Y", strtotime($event["event_date"])) ?> - <?= $event["event_type"] ?></strong><br>
                <small>Doctor: <?= $event["doctor_name"] ?: "N/A" ?> | Hospital: <?= $event["hospital_name"] ?: "N/A" ?></small><br>
                <small>Bill: ₹<?= number_format($event["bill_amount"], 2) ?></small>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<!-- Footer -->
<div class="footer">© 2024 PresCare | Your Personal Health Companion</div>

</body>
</html>
