<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

$username = $_SESSION['username'];
$gender = $_SESSION['gender'];
$age = $_SESSION['age'];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Home</title>
    <link rel="stylesheet" href="Home.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
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

    <!-- Sidebar -->
    <div class="left-bar">
        <div class="user-profile">
            <img class="mainProfileImg" src="svg/profile.svg" alt="Profile Image">
            <h6><?php echo htmlspecialchars($username); ?></h6>
            <p><?php echo htmlspecialchars($gender) . ", " . htmlspecialchars($age) . " years"; ?></p>
        </div>
        <ul class="menuList">
            <li><a href="home.php">Home</a></li>
            <li><a href="workouts.php">Workouts</a></li>
            <li><a href="diet.php">Diet</a></li>
        </ul>

        <h5 id="month-name"></h5>
        <div id="heatmap" class="heatmap"></div>
    </div>

    <!-- Main Content -->
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

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 FitTrack. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Date Selection
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

            // Slideshow
            // const slideshow = document.getElementById('slideshow');
            // let currentIndex = 0;
            // const cards = slideshow.children;
            // const totalCards = cards.length;

            // function scrollToNextCard() {
            //     currentIndex = (currentIndex + 1) % totalCards;
            //     slideshow.style.transition = "transform 0.5s ease-in-out";
            //     slideshow.style.transform = `translateX(${-currentIndex * 100}%)`;
            // }

            // setInterval(scrollToNextCard, 2000);
        });
    </script>

</body>
</html>
