<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login if not logged in
    exit();
}
$username = $_SESSION['username'];
$gender = $_SESSION['gender'];
$age = $_SESSION['age'];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: register.html"); // Redirect to register page on logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <title>FitTrack</title>
</head>

<body>
    <nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
        <div class="navbar-brand">FitTrack</div>
        <div class="calendar-section">
            <i class="bi bi-calendar3"></i>
            <span class="date" id="selected-date"></span>
        </div>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
        </form>
    </nav>

    <div class="left-bar">
        <div class="user-profile">
            <img class="mainProfileImg" src="svg/profile.svg" alt="Profile Image">
            <h6><?php echo $username; ?></h6>
            <p><?php echo $gender . ", " . $age . " years"; ?></p>
        </div>
        <ul class="menuList">
            <li>Home</li>
            <li>Workouts</li>
            <li>Diet</li>
        </ul>

        <h5 id="month-name"></h5>
        <div id="heatmap" class="heatmap"></div>
    </div>

    <div class="content">
        <h4>Main Content</h4>
        <div class="slideshow-container">
            <div class="slideshow" id="slideshow">
                <div class="card"><a href="Exercise.html">My Workouts</a></div>
                <div class="card"><a href="Meal.html">My Meals</a></div>
                <div class="card">Card 3</div>
                <div class="card">Card 4</div>
                <div class="card">Card 5</div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        document.addEventListener("DOMContentLoaded", function () {
            const today = new Date();
            document.getElementById("selected-date").textContent = `Today, ${formatDate(today)}`;

            flatpickr("#selected-date", {
                onChange: function (selectedDates, dateStr) {
                    document.getElementById("selected-date").textContent = dateStr;
                }
            });
        });
    </script>
</body>
</html>
