<?php include "../config/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PresCare - Register</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #63FFB7;
            --btn-hover: #50E0A0;
            --text-color: #333;
            --shadow-light: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e3fcef);
            color: var(--text-color);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            padding: 30px;
            animation: slideUp 0.6s ease-in-out;
            width: 100%;
            max-width: 480px;
            background: white;
        }

        .brand-title {
            font-family: 'League Spartan', sans-serif;
            font-weight: 600;
            font-size: 30px;
            color: var(--text-color);
            text-transform: lowercase;
            letter-spacing: -1.5px;
            text-align: center;
        }

        .brand-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0px 0px 8px rgba(99, 255, 183, 0.5);
        }

        .btn-custom {
            background: var(--primary-color);
            color: black;
            border-radius: 8px;
            font-weight: bold;
            padding: 12px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background: var(--btn-hover);
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(99, 255, 183, 0.5);
        }

        .card-footer {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }

        .card-footer a {
            text-decoration: none;
            color: var(--primary-color);
            font-weight: bold;
            transition: color 0.3s ease-in-out;
        }

        .card-footer a:hover {
            color: var(--btn-hover);
        }

        /* PresCare Loading Animation */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'League Spartan', sans-serif;
            font-size: 36px;
            font-weight: 600;
            letter-spacing: -1.5px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.5s ease-in-out;
        }

        .loading-screen.show {
            opacity: 1;
            visibility: visible;
        }

        .loading-text {
            animation: fadeText 1.5s infinite alternate;
        }

        @keyframes fadeText {
            from { opacity: 0.3; }
            to { opacity: 1; }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Loading Animation -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-text">prescare</div>
</div>

<!-- Registration Form -->
<div class="card">
    <div class="brand-title">prescare</div>
    <div class="brand-subtitle">Your Personal Health Companion</div>

    <div class="card-body">
        <form id="registerForm" method="POST" action="../controllers/register_process.php">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Blood Group</label>
                <input type="text" class="form-control" name="bloodgroup">
            </div>
            <div class="mb-3">
                <label class="form-label">Height (cm)</label>
                <input type="text" class="form-control" name="height">
            </div>
            <div class="mb-3">
                <label class="form-label">Weight (kg)</label>
                <input type="text" class="form-control" name="weight">
            </div>
            <button type="submit" class="btn btn-custom w-100">Register</button>
        </form>
    </div>

    <div class="card-footer">
        Already have an account? <a href="login.php">Login here</a>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById("registerForm").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default submission

        // Show loading animation
        document.getElementById("loadingScreen").classList.add("show");

        // Simulate form submission delay
        setTimeout(() => {
            this.submit(); // Submit after animation
        }, 1500);
    });
</script>

</body>
</html>
