<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}


// Prevent back button from accessing a logged-out session
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userId = $_SESSION["user"];
$result = $conn->query("SELECT * FROM users WHERE id='$userId'");
$user = $result->fetch_assoc();

// Fetch latest medical records (5 recent entries)
$records = $conn->query("SELECT * FROM medical_records WHERE user_id='$userId' ORDER BY uploaded_at DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Calculate BMI
$heightMeters = $user["height"] ? $user["height"] / 100 : 0;
$weightKg = $user["weight"] ?: 0;
$bmi = ($heightMeters > 0 && $weightKg > 0) ? round($weightKg / ($heightMeters * $heightMeters), 1) : "N/A";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>prescare - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@700&display=swap" rel="stylesheet">
    <style>
        /* Theme Colors */
        :root {
            --primary-color: #63FFB7;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --text-color: #000000;
            --border-color: rgba(255, 255, 255, 0.3);
            --btn-hover: #50E0A0;
            --shadow-color: rgba(0, 0, 0, 0.15);
        }

        body {
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-color);
            animation: fadeInBody 0.6s ease-in-out forwards;
        }

        /* Custom prescare Styling */
        .brand-text {
            font-family: 'League Spartan', sans-serif;
            font-weight: bold;
            font-size: 1.6rem;
            letter-spacing: -1.5px;
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

        /* Hero Section */
.hero {
    background: linear-gradient(135deg, rgba(99, 255, 183, 0.9), rgba(99, 255, 183, 0.8));
    color: var(--text-color);
    text-align: center;
    padding: 70px 20px;
    font-weight: bold;
    backdrop-filter: blur(10px);
    border-radius: 15px;
    box-shadow: 0 10px 20px var(--shadow-color);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 1s ease-in-out forwards, floating 4s infinite ease-in-out, gradientGlow 5s infinite alternate;
}

/* Animated Gradient Glow */
@keyframes gradientGlow {
    0% { background: linear-gradient(135deg, rgba(99, 255, 183, 0.9), rgba(99, 255, 183, 0.8)); }
    100% { background: linear-gradient(135deg, rgba(99, 255, 183, 0.8), rgba(99, 255, 183, 1)); }
}

/* Hero Title Animation */
.hero h1 {
    font-family: 'League Spartan', sans-serif;
    letter-spacing: -1.5px;
    font-size: 2.5rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInText 1s ease-out 0.5s forwards, scaleIn 1.2s ease-in-out, shimmerText 3s infinite alternate;
}

/* Hero Subtext */
.hero p {
    font-size: 1.2rem;
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInText 1.2s ease-out 0.8s forwards;
}

/* Floating Effect */
@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
}

/* Fade-In Text */
@keyframes fadeInText {
    0% { opacity: 0; transform: translateY(20px); }
    100% { opacity: 1; transform: translateY(0px); }
}

/* Soft Glow Animation */
.hero::after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    z-index: -1;
    animation: softGlow 3s infinite ease-in-out;
}

@keyframes softGlow {
    0% { box-shadow: 0 0 10px rgba(99, 255, 183, 0.3); }
    50% { box-shadow: 0 0 20px rgba(99, 255, 183, 0.5); }
    100% { box-shadow: 0 0 10px rgba(99, 255, 183, 0.3); }
}

/* Ripple Hover Effect */
.hero:hover {
    animation: rippleEffect 0.8s ease-out;
}

@keyframes rippleEffect {
    0% { box-shadow: 0px 0px 0px rgba(99, 255, 183, 0.4); }
    100% { box-shadow: 0px 0px 20px rgba(99, 255, 183, 0.8); }
}

/* Shimmer Text Effect */
@keyframes shimmerText {
    0% { text-shadow: 0px 0px 10px rgba(255, 255, 255, 0.2); }
    100% { text-shadow: 0px 0px 20px rgba(255, 255, 255, 0.6); }
}

/* Wave Background Animation */
.hero::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    animation: waveMotion 4s infinite linear;
}

@keyframes waveMotion {
    0% { transform: translateX(-10px); }
    50% { transform: translateX(10px); }
    100% { transform: translateX(-10px); }
}

/* Scale-In Effect */
@keyframes scaleIn {
    0% { transform: scale(0.9); }
    100% { transform: scale(1); }
}

/* Floating Particles */
.hero::after {
    content: "";
    position: absolute;
    top: -10px;
    left: 50%;
    width: 5px;
    height: 5px;
    background: white;
    border-radius: 50%;
    animation: floatingParticles 4s infinite ease-in-out;
}

@keyframes floatingParticles {
    0% { transform: translateY(0px) scale(1); opacity: 0.8; }
    50% { transform: translateY(-15px) scale(1.2); opacity: 1; }
    100% { transform: translateY(0px) scale(1); opacity: 0.8; }
}


        /* Cards */
.card {
    background: var(--secondary-color);
    backdrop-filter: blur(12px);
    color: var(--text-color);
    border: 1px solid var(--border-color);
    box-shadow: 0px 4px 12px var(--shadow-color);
    border-radius: 15px;
    animation: fadeIn 0.6s ease-in-out forwards, popUp 0.8s ease-in-out;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out, background 0.3s ease-in-out;
    position: relative;
    overflow: hidden;
    padding: 20px;
}

/* Subtle Hover Effects */
.card:hover {
    transform: translateY(-7px) scale(1.02);
    box-shadow: 0px 12px 30px rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.8);
}

/* Glowing Border Effect */
.card::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 250%;
    height: 250%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 10%, transparent 70%);
    transition: 0.5s;
    transform: translate(-50%, -50%) scale(0);
    border-radius: 50%;
    z-index: 1;
    pointer-events: none;
}

/* Active Animation */
.card:hover::before {
    transform: translate(-50%, -50%) scale(1);
}

/* Subtle Pop Animation on Load */
@keyframes popUp {
    from {
        transform: scale(0.9);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

/* Fade In Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Smooth Shadow Transition */
.card:active {
    transform: scale(0.98);
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
}

/* Modern Title Styling */
.card-header {
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 10px;
}

/* Soft Gradient Background */
.card-body {
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.8), rgba(250, 250, 250, 0.6));
    padding: 15px;
    border-radius: 10px;
}


        /* Footer */
        .footer {
            background: rgba(99, 255, 183, 0.9);
            color: var(--text-color);
            text-align: center;
            padding: 15px;
            margin-top: 30px;
            font-weight: bold;
            border-radius: 10px;
            box-shadow: 0px 5px 15px var(--shadow-color);
        }
        /* Loading Animation */
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
    opacity: 1;
    visibility: visible;
    z-index: 9999;
    transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
}

.loading-text {
    animation: fadeText 1.5s infinite alternate;
}

@keyframes fadeText {
    from { opacity: 0.3; }
    to { opacity: 1; }
}

    </style>
</head>

<body>
    <!-- Loading Animation -->
<div class="loading-screen" id="loadingScreen">
    <div class="loading-text">prescare</div>
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

<!-- Hero Banner -->
<div class="container mt-5">
    <div class="hero">
        <h1>Welcome to prescare</h1>
        <p>Your Personal Health Companion – Track, Manage & Secure Your Medical Records</p>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center">Hello, <?= htmlspecialchars($user["username"]) ?>!</h2>

    <div class="row mt-4">
        <!-- Health Summary -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <div class="card-header text-center">
                    <h5>Health Summary</h5>
                </div>
                <div class="card-body">
                    <p><strong>Height:</strong> <?= $user["height"] ?: "Not Specified" ?> cm</p>
                    <p><strong>Weight:</strong> <?= $user["weight"] ?: "Not Specified" ?> kg</p>
                    <p><strong>BMI:</strong> <?= $bmi ?></p>
                    <p><strong>Blood Pressure:</strong> <?= $user["blood_pressure"] ?: "Not Specified" ?></p>
                </div>
            </div>
        </div>

        <!-- Recent Medical Records -->
        <div class="col-md-6">
            <div class="card shadow p-4">
                <div class="card-header text-center">
                    <h5>Recent Medical Records</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($records)) { ?>
                        <ul class="list-group">
                            <?php foreach ($records as $record) { ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($record["category"]) ?></strong> - <?= date("M d, Y", strtotime($record["uploaded_at"])) ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <p>No medical records uploaded yet.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2025 prescare | Your Personal Health Companion</p>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            document.getElementById("loadingScreen").style.opacity = "0";
            document.getElementById("loadingScreen").style.visibility = "hidden";
        }, 1000); // Animation lasts for 1 second before disappearing
    });
</script>
</body>
</html>
