<?php include "../config/db.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PresCare - Login</title>

    <!-- Google Font: League Spartan -->
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #63FFB7;
            --btn-hover: #50E0A0;
            --text-color: #333;
            --shadow-light: 0px 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Minimalistic Modern Background */
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle, rgba(243, 249, 245, 1) 0%, rgba(231, 241, 236, 1) 100%);
            color: var(--text-color);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Card Animation */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            padding: 30px;
            animation: slideUp 0.6s ease-in-out;
            width: 100%;
            max-width: 420px;
            background: white;
            text-align: center;
            position: relative;
        }

        /* Branding Glow Effect */
        .brand-title {
            font-family: 'League Spartan', sans-serif;
            font-weight: 600;
            font-size: 28px;
            color: var(--text-color);
            text-transform: lowercase;
            letter-spacing: -1.5px;
            animation: glow 2s ease-in-out infinite alternate;
        }

        .brand-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        /* Input Fields with Focus Animation */
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
            animation: bounce 0.3s ease-in-out;
        }

        /* Buttons */
        .btn-custom {
            background: var(--primary-color);
            color: black;
            border-radius: 8px;
            font-weight: bold;
            padding: 10px;
            font-size: 14px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            width: 80%;
            margin-top: 10px;
        }

        .btn-custom:hover {
            background: var(--btn-hover);
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(99, 255, 183, 0.5);
        }

        /* Ripple Effect */
        .btn-custom:after {
            content: "";
            position: absolute;
            width: 300%;
            height: 300%;
            top: 50%;
            left: 50%;
            background: rgba(255, 255, 255, 0.5);
            transition: transform 0.6s, opacity 0.6s;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
            opacity: 0;
        }

        .btn-custom:active:after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
            transition: 0s;
        }

        /* Register Button */
        .btn-register {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: bold;
            transition: color 0.3s ease-in-out;
            margin-top: 10px;
            font-size: 14px;
        }

        .btn-register:hover {
            color: var(--btn-hover);
            text-decoration: underline;
        }

        /* Admin Switch */
        .admin-switch {
            background: none;
            border: none;
            color: var(--primary-color);
            font-weight: bold;
            transition: color 0.3s ease-in-out;
            margin-top: 10px;
            font-size: 14px;
        }

        .btn-register:hover {
            color: var(--btn-hover);
            text-decoration: underline;
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

        .loading-logo {
            font-family: 'League Spartan', sans-serif;
            font-weight: bold;
            font-size: 36px;
            letter-spacing: -1.5px;
            color: var(--primary-color);
            opacity: 0;
            animation: fadeInLogo 1s ease-in-out forwards;
        }

        .loading-spinner {
            border: 5px solid rgba(99, 255, 183, 0.5);
            border-top: 5px solid var(--primary-color);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin-top: 20px;
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

        @keyframes fadeInLogo {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        @keyframes glow {
            0% { text-shadow: 0px 0px 10px rgba(99, 255, 183, 0.5); }
            100% { text-shadow: 0px 0px 15px rgba(99, 255, 183, 0.8); }
        }
    </style>
</head>
<body>

<!-- Loading Animation -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-logo">prescare</div>
    <div class="loading-spinner"></div>
</div>

<div class="card">
    <!-- Branding -->
    <div class="brand-title">prescare</div>
    <div class="brand-subtitle">Your Personal Health Companion</div>

    <!-- Login Form -->
    <div class="card-body">
        <form id="loginForm" method="POST" action="../controllers/login_process.php">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-custom">Login</button><br><br>
            <a href="register.php" class="btn-register">Create an account</a><br>
            <a href="admin_login.php" class="admin-switch">Switch to Admin Login</a>
        </form>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(event) {
        event.preventDefault();
        document.getElementById('loadingOverlay').style.display = 'flex';
        setTimeout(() => { this.submit(); }, 2000);
    });
</script>

</body>
</html>
