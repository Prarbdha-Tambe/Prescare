<?php
include "../config/db.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $specialization = $_POST["specialization"];
    $hospital = $_POST["hospital"];
    $contact = $_POST["contact"];

    if (!empty($name) && !empty($specialization) && !empty($hospital) && !empty($contact)) {
        $stmt = $conn->prepare("INSERT INTO doctors (name, specialization, hospital, contact) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $specialization, $hospital, $contact);
        if ($stmt->execute()) {
            echo "<script>showSuccess();</script>"; // Show animation before redirecting
            echo "<script>setTimeout(() => window.location.href = 'doctors.php', 2000);</script>";
            exit;
        } else {
            $error = "Failed to add doctor.";
        }
        $stmt->close();
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; color: #333; }
        .navbar { background-color: #63FFB7 !important; }
        .navbar-brand, .nav-link { color: black !important; font-weight: bold; }
        .container { margin-top: 40px; }
        .card { border-radius: 12px; box-shadow: 0px 6px 14px rgba(0, 0, 0, 0.1); padding: 25px; transition: 0.3s; }
        .card:hover { box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15); transform: translateY(-2px); }
        .btn-custom { background: #63FFB7; color: black; border-radius: 8px; font-weight: bold; padding: 12px; transition: 0.3s; }
        .btn-custom:hover { background: #50E0A0; transform: scale(1.05); }
        .form-control { border-radius: 8px; transition: 0.3s; padding: 12px; }
        .form-control:focus { box-shadow: 0px 0px 8px rgba(99, 255, 183, 0.5); border-color: #63FFB7; }
        .footer { background-color: #63FFB7; color: black; text-align: center; padding: 15px; margin-top: 30px; font-weight: bold; }
        .alert-danger { background-color: #ffdddd; color: #900; border-left: 5px solid red; }

        /* Success Animation */
        .success-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            justify-content: center;
            align-items: center;
            font-size: 24px;
            font-weight: bold;
            color: black;
            z-index: 9999;
            flex-direction: column;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<!-- Success Animation -->
<div class="success-overlay" id="successOverlay">
    <span>Doctor Added Successfully!</span>
    <br>
    <span>Redirecting...</span>
</div>

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
<div class="container text-center mt-4">
    <h2 style="color: black;">Add New Doctor</h2>
    <p>Fill in the details to add a new doctor.</p>
</div>

<!-- Form to Add Doctor -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Doctor's Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hospital</label>
                            <input type="text" class="form-control" name="hospital" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact" required>
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Add Doctor</button>
                    </form>

                    <!-- Back Button -->
                    <div class="text-center mt-3">
                        <a href="doctors.php" class="btn btn-secondary">← Back to Doctors Directory</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2024 PresCare | Your Personal Health Companion</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showSuccess() {
        document.getElementById("successOverlay").style.display = "flex";
    }
</script>

</body>
</html>
