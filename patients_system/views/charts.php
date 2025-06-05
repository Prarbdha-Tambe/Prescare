<?php
include "../config/db.php";
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION["user"];

// Fetch Medical History for Expenses
$history = $conn->query("SELECT event_date, bill_amount FROM medical_history WHERE user_id='$userId' ORDER BY event_date ASC")->fetch_all(MYSQLI_ASSOC);

// Prepare Data for Charts
$expensesYearly = [];
$expensesMonthly = [];
$appointments = [];

foreach ($history as $record) {
    $year = date("Y", strtotime($record["event_date"]));
    $month = date("Y-m", strtotime($record["event_date"]));
    $eventDate = date("Y-m-d", strtotime($record["event_date"]));

    $expensesYearly[$year] = ($expensesYearly[$year] ?? 0) + $record["bill_amount"];
    $expensesMonthly[$month] = ($expensesMonthly[$month] ?? 0) + $record["bill_amount"];

    if (strtotime($eventDate) >= strtotime(date("Y-m-d"))) {
        $appointments[] = [
            "title" => "Appointment",
            "start" => $eventDate
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Analytics & Appointments</title>
    
    <!-- Bootstrap, Chart.js, FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>

    <style>
        :root {
            --primary-color: #63FFB7;
            --secondary-color: rgba(255, 255, 255, 0.7);
            --text-color: #000;
            --border-color: rgba(255, 255, 255, 0.3);
            --btn-hover: #50E0A0;
            --shadow-color: rgba(0, 0, 0, 0.15);
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: var(--primary-color) !important; box-shadow: 0 4px 10px var(--shadow-color); }
        .navbar-brand { font-family: 'League Spartan', sans-serif; font-weight: bold; font-size: 1.9rem; letter-spacing: -1.5px; }
        .navbar-nav .nav-link { font-weight: 500; transition: color 0.3s ease-in-out; }
        .navbar-nav .nav-link:hover { color: var(--btn-hover) !important; }
        .container { margin-top: 40px; }
        .footer { background-color: #63FFB7; color: black; text-align: center; padding: 15px; margin-top: 30px; font-weight: bold; }
        .dashboard-row { display: flex; flex-wrap: wrap; gap: 20px; }
        .chart-card, .calendar-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1); width: 48%; }
        .chart-card:hover, .calendar-card:hover { transform: translateY(-5px); box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.2); }
        .fade-in { opacity: 0; transform: translateY(20px); transition: opacity 0.6s ease-out, transform 0.6s ease-out; }
        .fade-in.show { opacity: 1; transform: translateY(0); }
        #calendar { border-radius: 10px; overflow: hidden; box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1); }
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
    <h2 class="text-center" style="color: black;">Health Analytics & Appointments</h2>
    <p>Track your health expenses and upcoming appointments.</p>
</div>

<!-- Filter -->
<div class="container text-center">
    <label for="chartFilter"><strong>Filter by:</strong></label>
    <select class="form-select w-50 mx-auto" id="chartFilter">
        <option value="yearly">Yearly Expenses</option>
        <option value="monthly">Monthly Expenses</option>
    </select>
</div>

<!-- Dashboard Row -->
<div class="container dashboard-row mt-4">
    <div class="chart-card fade-in">
        <canvas id="expensesChart"></canvas>
    </div>
    <div class="calendar-card fade-in">
        <h5 class="text-center">Upcoming Appointments</h5>
        <div id="calendar"></div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>© 2024 PresCare | Your Personal Health Companion</p>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".fade-in").forEach(el => el.classList.add("show"));

    const ctx = document.getElementById('expensesChart').getContext('2d');

    const expensesYearly = {
        labels: <?= json_encode(array_keys($expensesYearly)) ?>,
        datasets: [{
            label: 'Yearly Expenses (₹)',
            data: <?= json_encode(array_values($expensesYearly)) ?>,
            backgroundColor: '#63FFB7',
            borderColor: '#50E0A0',
            borderWidth: 2
        }]
    };

    const expensesMonthly = {
        labels: <?= json_encode(array_keys($expensesMonthly)) ?>,
        datasets: [{
            label: 'Monthly Expenses (₹)',
            data: <?= json_encode(array_values($expensesMonthly)) ?>,
            backgroundColor: '#FFA500',
            borderColor: '#FF8C00',
            borderWidth: 2
        }]
    };

    let expensesChart = new Chart(ctx, {
        type: 'bar',
        data: expensesYearly,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1500, easing: 'easeOutQuart' },
            plugins: {
                legend: { labels: { color: '#333', font: { size: 14, weight: 'bold' } } },
                tooltip: {
                    backgroundColor: '#000',
                    titleColor: '#FFF',
                    bodyColor: '#FFF',
                    borderColor: '#63FFB7',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12
                }
            },
            scales: {
                x: { ticks: { color: '#333' }, grid: { display: false } },
                y: { beginAtZero: true, max: 500000, ticks: { stepSize: 50000, color: '#333' }, grid: { color: 'rgba(0,0,0,0.1)', borderDash: [5, 5] } }
            },
            elements: {
                bar: {
                    borderRadius: 8,
                    backgroundColor: (context) => {
                        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, '#63FFB7');
                        gradient.addColorStop(1, '#50E0A0');
                        return gradient;
                    }
                }
            }
        }
    });

    document.getElementById('chartFilter').addEventListener('change', function () {
        expensesChart.data = this.value === 'yearly' ? expensesYearly : expensesMonthly;
        expensesChart.update();
    });

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next', center: 'title', right: '' },
        events: <?= json_encode($appointments) ?>,
        eventColor: '#63FFB7',
        eventTextColor: '#000',
        height: 'auto',
        eventDidMount: function (info) {
            info.el.style.borderRadius = '6px';
            info.el.style.boxShadow = '0px 3px 8px rgba(0,0,0,0.1)';
            info.el.style.padding = '5px';
        }
    });

    calendar.render();
});
</script>

</body>
</html>
