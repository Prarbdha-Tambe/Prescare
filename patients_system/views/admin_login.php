<?php
session_start();

// If admin is already logged in, redirect to dashboard
if (isset($_SESSION["admin"])) {
    header("Location: admin_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>prescare admin login</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #F96900;
            --secondary-color: #ffffff;
            --text-color: #333;
            --shadow-light: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'League Spartan', sans-serif;
            background: linear-gradient(to bottom, #fffaf3, #f8f9fa);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeInBody 0.8s ease-in-out;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            padding: 30px;
            background: var(--secondary-color);
            width: 100%;
            max-width: 420px;
            text-align: center;
            animation: fadeInUp 0.6s ease-in-out;
        }

        .brand-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--primary-color);
            letter-spacing: -1.5px;
            margin-bottom: 10px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0px 0px 10px rgba(249, 105, 0, 0.5);
        }

        .btn-custom {
            background: var(--primary-color);
            color: white;
            border-radius: 8px;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        .btn-custom:hover {
            background: #d45a00;
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(249, 105, 0, 0.4);
        }

        .card-footer {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
            color: #666;
        }

        /* Loading Animation */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
        }

        .loading-text {
            font-size: 26px;
            font-weight: bold;
            color: var(--primary-color);
            opacity: 0;
            animation: fadeInText 1s ease-in-out forwards;
        }

        .loading-spinner {
            border: 5px solid rgba(249, 105, 0, 0.3);
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
        }

        /* Animations */
        @keyframes fadeInBody {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInText {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>

<!-- Loading Animation -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-text">prescare</div>
    <div class="loading-spinner"></div>
</div>

<!-- Login Card -->
<div class="card">
    <div class="brand-title">prescare<br>Admin Login</div>

    <form id="loginForm" action="../controllers/admin_login_process.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-custom">Login</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        // Show loading animation
        document.getElementById('loadingOverlay').style.display = 'flex';

        // Simulate login delay before submitting
        setTimeout(() => {
            this.submit(); // Submit form after animation
        }, 2000);
    });
</script>

</body>
</html>
