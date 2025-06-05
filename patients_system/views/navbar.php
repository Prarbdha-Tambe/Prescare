<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PresCare</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome for Icons (Optional) -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Custom Styles -->
    <style>
        /* Navbar Styling */
        .navbar {
            background-color: #63FFB7 !important;
            padding: 15px;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: black !important;
            font-size: 1.5rem;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border-radius: 10px;
            border: none;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            transition: background 0.3s ease-in-out;
        }
        
        .dropdown-item:hover {
            background: #50E0A0;
            color: black !important;
        }

        /* Make Dropdown Button Bigger */
        .nav-link {
            font-size: 1.2rem;
            font-weight: 600;
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="homepage.php">PresCare</a>
        
        <!-- Menu Button for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            Menu
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <!-- Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navDropdown" role="button" data-bs-toggle="dropdown">
                        Menu
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="medical_history.php">Medical History</a></li>
                        <li><a class="dropdown-item" href="medical_records.php">Medical Records</a></li>
                        <li><a class="dropdown-item" href="doctors.php">Doctors Directory</a></li>
                        <li><a class="dropdown-item" href="charts.php">Health Charts</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JS (Must be placed at the end of the body) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
