<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: admin_login.php");
    exit;
}

$adminId = $_SESSION["admin"];
$result = $conn->query("SELECT * FROM admins WHERE id='$adminId'");
$admin = $result->fetch_assoc();

// Handle Profile Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    if ($password === $confirmPassword) {
        // Hash password if changed
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE admins SET username='$username', email='$email', password='$hashedPassword' WHERE id='$adminId'";
        } else {
            $updateQuery = "UPDATE admins SET username='$username', email='$email' WHERE id='$adminId'";
        }

        if ($conn->query($updateQuery)) {
            $_SESSION["success"] = "Profile updated successfully!";
            header("Location: admin_profile.php");
            exit;
        } else {
            $error = "Error updating profile!";
        }
    } else {
        $error = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Admin Profile - prescare</title>

    <!-- Bootstrap & Google Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --hover-color: #F17F29;
            --text-color: #333;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background-color: #f8f9fa;
            animation: fadeIn 0.6s ease-in-out;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
        }

        .card {
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0px 5px 12px var(--shadow-color);
            animation: fadeInUp 0.8s ease-in-out;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: var(--hover-color);
            transform: scale(1.05);
        }

        .btn-secondary:hover {
            transform: scale(1.05);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Edit Admin Profile</h2>

    <div class="card">
        <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($admin["username"]) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($admin["email"]) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password (optional)</label>
                <input type="password" class="form-control" name="password">
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password">
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>
    </div>

    <div class="text-center mt-4">
        <a href="admin_profile.php" class="btn btn-secondary">Cancel</a>
    </div>
</div>

</body>
</html>
