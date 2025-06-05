<?php
include "../config/db.php";
session_start();
if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

$adminId = $_SESSION["admin"]; // Store admin ID

// Handle Doctor Addition
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $specialization = $_POST["specialization"];
    $hospital = $_POST["hospital"];
    $contact = $_POST["contact"];

    if (!empty($name) && !empty($specialization) && !empty($hospital) && !empty($contact)) {
        $stmt = $conn->prepare("INSERT INTO doctors (name, specialization, hospital, contact, added_by) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $name, $specialization, $hospital, $contact, $adminId);
        
        if ($stmt->execute()) {
            header("Location: admin_doctors.php?success=Doctor added successfully");
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
    <title>Add Doctor - prescare</title>

    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --hover-color: #F17F29;
            --text-color: #333;
            --shadow-color: rgba(0, 0, 0, 0.15);
            --input-bg: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background: var(--input-bg);
            color: var(--text-color);
            animation: fadeIn 0.6s ease-in-out;
        }

        .container {
            margin-top: 50px;
            max-width: 500px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 6px 15px var(--shadow-color);
            animation: slideUp 0.8s ease-in-out;
        }

        h2 {
            font-weight: bold;
            letter-spacing: -1.5px;
            color: var(--primary-color);
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid var(--shadow-color);
            background: var(--input-bg);
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0px 0px 10px rgba(249, 105, 0, 0.3);
        }

        .btn-submit {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
            transition: all 0.3s ease-in-out;
        }

        .btn-submit:hover {
            background: var(--hover-color);
            transform: scale(1.05);
            box-shadow: 0px 4px 10px var(--shadow-color);
        }

        .btn-back {
            background: black;
            color: white;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .btn-back:hover {
            background: var(--hover-color);
            transform: scale(1.05);
        }

        .alert {
            text-align: center;
            font-weight: bold;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>+ Add New Doctor</h2>

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
        <button type="submit" class="btn btn-submit w-100">Add Doctor</button>
    </form>

    <!-- Back Button -->
    <div class="text-center mt-3">
        <a href="admin_doctors.php" class="btn btn-back">← Back to Doctors</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
