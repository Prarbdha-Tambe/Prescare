<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user"];
$categories = ["Prescriptions", "Consultations", "Records", "Files"];

// Get selected category (default to Prescriptions)
$selectedCategory = isset($_GET["category"]) ? $_GET["category"] : "Prescriptions";

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    $category = $_POST["category"];
    $image = file_get_contents($_FILES["image"]["tmp_name"]);

    $stmt = $conn->prepare("INSERT INTO medical_records (user_id, category, image) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $category, $image);

    if ($stmt->execute()) {
        $message = "Image uploaded successfully!";
    } else {
        $message = "Error uploading image!";
    }
    $stmt->close();
}

// Handle delete request
if (isset($_GET["delete"])) {
    $recordId = $_GET["delete"];
    $stmt = $conn->prepare("DELETE FROM medical_records WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $recordId, $userId);

    if ($stmt->execute()) {
        $message = "Image deleted successfully!";
    } else {
        $message = "Error deleting image!";
    }
    $stmt->close();
}

// Fetch images for the selected category
$stmt = $conn->prepare("SELECT * FROM medical_records WHERE user_id = ? AND category = ? ORDER BY uploaded_at DESC");
$stmt->bind_param("is", $userId, $selectedCategory);
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Medical Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #63FFB7;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --text-color: #000000;
            --border-color: rgba(255, 255, 255, 0.3);
            --btn-hover: #50E0A0;
            --shadow-color: rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
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
        .container {
            margin-top: 40px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .btn-custom {
            background: #63FFB7;
            color: black;
            border-radius: 8px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(99, 255, 183, 0.5);
        }

        .nav-tabs .nav-link {
            color: black;
            font-weight: bold;
        }

        .nav-tabs .nav-link.active {
            background-color: #63FFB7;
            color: black;
            border-radius: 8px;
        }

        .footer {
            background-color: #63FFB7;
            color: black;
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            font-weight: bold;
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
<div class="container text-center mt-4">
    <h2 style="color: black;">Medical Records</h2>
    <p>Upload and manage your health documents securely.</p>
</div>

<!-- Category Tabs -->
<ul class="nav nav-tabs mt-3 justify-content-center">
    <?php foreach ($categories as $category) { ?>
        <li class="nav-item">
            <a class="nav-link <?= ($selectedCategory == $category) ? 'active' : '' ?>" 
                href="?category=<?= $category ?>"><?= $category ?></a>
        </li>
    <?php } ?>
</ul>

<!-- Upload Form -->
<div class="container">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Upload to <?= $selectedCategory ?></h5>
            <?php if (isset($message)) { echo "<p class='text-success'>$message</p>"; } ?>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="category" value="<?= $selectedCategory ?>">
                <div class="mb-3">
                    <label class="form-label">Choose Image</label>
                    <input type="file" class="form-control" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Upload</button>
            </form>
        </div>
    </div>
</div>

<!-- Display Uploaded Images -->
<div class="container mt-5">
    <h4><?= $selectedCategory ?> Files</h4>
    <div class="row">
        <?php if (!empty($records)) { ?>
            <?php foreach ($records as $record) { ?>
                <div class="col-md-4 mt-3">
                    <div class="card shadow">
                        <div class="card-body text-center">
                            <h6 class="card-title"><?= htmlspecialchars($record["category"]) ?></h6>
                            <img src="view_image.php?id=<?= $record["id"] ?>" class="img-fluid rounded mb-2">
                            <p class="small text-muted">Uploaded on <?= date("M d, Y", strtotime($record["uploaded_at"])) ?></p>
                            <a href="medical_records.php?category=<?= $selectedCategory ?>&delete=<?= $record["id"] ?>" 
                                class="btn btn-danger w-100 mt-2">Delete</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p class="text-center text-muted">No medical records uploaded yet.</p>
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
