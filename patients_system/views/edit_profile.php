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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $date_of_birth = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $emergency_contact_name = $_POST["emergency_contact_name"];
    $emergency_contact_phone = $_POST["emergency_contact_phone"];
    $bloodgroup = $_POST["bloodgroup"];
    $blood_pressure = $_POST["blood_pressure"];
    $height = $_POST["height"];
    $weight = $_POST["weight"];
    $existing_conditions = $_POST["existing_conditions"];
    $allergies = $_POST["allergies"];
    $medical_conditions = $_POST["medical_conditions"];

    $sql = "UPDATE users SET 
            email='$email', 
            date_of_birth='$date_of_birth', 
            gender='$gender', 
            phone='$phone', 
            address='$address', 
            emergency_contact_name='$emergency_contact_name', 
            emergency_contact_phone='$emergency_contact_phone', 
            bloodgroup='$bloodgroup', 
            blood_pressure='$blood_pressure', 
            height='$height', 
            weight='$weight', 
            existing_conditions='$existing_conditions', 
            allergies='$allergies', 
            medical_conditions='$medical_conditions' 
            WHERE id='$userId'";

    if ($conn->query($sql) === TRUE) {
        header("Location: profile.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #63FFB7;
            --secondary-color: #FFFFFF;
            --text-color: #000000;
            --border-color: #CCCCCC;
            --btn-hover: #50E0A0;
        }

        body {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .navbar {
            background-color: var(--primary-color) !important;
        }

        .navbar-brand, .nav-link {
            color: var(--text-color) !important;
            font-weight: bold;
        }

        .card {
            border-radius: 10px;
            background: var(--secondary-color);
            color: var(--text-color);
            border: 2px solid var(--border-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--text-color);
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: var(--btn-hover);
            border-color: var(--btn-hover);
        }

        .footer {
            background-color: var(--primary-color);
            color: var(--text-color);
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">PresCare</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="medical_history.php">Medical History</a></li>
                <li class="nav-item"><a class="nav-link" href="medical_records.php">Medical Records</a></li>
                <li class="nav-item"><a class="nav-link btn btn-dark text-white" href="../controllers/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Edit Profile Form -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h2>Edit Profile</h2>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <?php
                        $fields = [
                            "email" => "Email",
                            "date_of_birth" => "Date of Birth",
                            "gender" => "Gender",
                            "phone" => "Phone",
                            "address" => "Address",
                            "emergency_contact_name" => "Emergency Contact Name",
                            "emergency_contact_phone" => "Emergency Contact Phone",
                            "bloodgroup" => "Blood Group",
                            "blood_pressure" => "Blood Pressure",
                            "height" => "Height (cm)",
                            "weight" => "Weight (kg)",
                            "existing_conditions" => "Existing Conditions",
                            "allergies" => "Allergies",
                            "medical_conditions" => "Medical Conditions"
                        ];
                        
                        foreach ($fields as $key => $label) {
                            if ($key == "gender") { ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= $label ?></label>
                                    <select class="form-control" name="<?= $key ?>">
                                        <option value="Male" <?= ($user[$key] == "Male") ? "selected" : "" ?>>Male</option>
                                        <option value="Female" <?= ($user[$key] == "Female") ? "selected" : "" ?>>Female</option>
                                        <option value="Other" <?= ($user[$key] == "Other") ? "selected" : "" ?>>Other</option>
                                    </select>
                                </div>
                            <?php } elseif ($key == "date_of_birth") { ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= $label ?></label>
                                    <input type="date" class="form-control" name="<?= $key ?>" value="<?= $user[$key] ?>">
                                </div>
                            <?php } elseif (in_array($key, ["address", "existing_conditions", "allergies", "medical_conditions"])) { ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= $label ?></label>
                                    <textarea class="form-control" name="<?= $key ?>"><?= $user[$key] ?></textarea>
                                </div>
                            <?php } else { ?>
                                <div class="mb-3">
                                    <label class="form-label"><?= $label ?></label>
                                    <input type="<?= ($key == 'email') ? 'email' : 'text' ?>" class="form-control" name="<?= $key ?>" value="<?= $user[$key] ?>">
                                </div>
                            <?php }
                        } ?>
                        <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <a href="profile.php" class="btn btn-secondary">Cancel</a>
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

</body>
</html>
