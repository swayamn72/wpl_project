<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];
$age = $_SESSION['age'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FitTrack - Sleep</title>
    <link rel="stylesheet" href="meal2.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="health.css">
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button id="closeSidebar">&times;</button>
        <div class="user-profile-sidebar">
            <div class="top-profile-sidebar">
                <img class="mainProfileImg-sidebar" src="svg/profile.svg" alt="Profile Image">
                <div class="name-age-sidebar">
                    <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
                    <p class="gender-age"><?php echo htmlspecialchars($age); ?> years old</p>
                </div>
            </div>
        </div>
        <ul class="menuList">
            <li><a href="home.php"><img src="svg/home.svg" alt="Home"> Home</a></li>
            <li><a href="Meal.html"><img src="svg/meals.svg" alt="Meals"> Meals</a></li>
            <li><a href="weekexercise.html"><img src="svg/dumbell.svg" alt="Exercises"> Exercises</a></li>
            <li><a href="achievements.html"><img src="svg/medal.svg" alt="Achievements"> Achievements</a></li>
            <li><a href="settings.html"><img src="svg/settings.svg" alt="Settings"> Settings</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
        <div class="navbar-brand">
            <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
            <div class="mainName">FitTrack - Sleep</div>
        </div>
        <div class="nav-right">
            <img src="svg/bell.svg" alt="">
            <img src="svg/dark.svg" alt="">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content container mt-4">
        <h2>Sleep Tracker</h2>

        <!-- Form to add sleep -->
        <form action="add_sleep.php" method="POST" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" name="sleep_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <input type="number" name="sleep_hours" step="0.1" min="0" max="24" class="form-control"
                        placeholder="Hours Slept" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Add Sleep</button>
                </div>
            </div>
        </form>


        <h2>Sleep Hours - Last 10 Days</h2>
        <canvas id="sleepChart" height="100"></canvas>


    </div>

    <script>
    document.getElementById("menuIcon").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "0";
    });
    document.getElementById("closeSidebar").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "-400px";
    });
    </script>
    <script>
fetch('fetch_sleep_data.php')
    .then(response => response.json())
    .then(data => {
        const labels = data.map(item => item.sleep_date);
        const sleepHours = data.map(item => item.sleep_hours);

        const ctx = document.getElementById('sleepChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Sleep Hours',
                    data: sleepHours,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10
                    }
                }
            }
        });
    })
    .catch(error => console.error('Error loading sleep data:', error));
</script>


</body>

</html>