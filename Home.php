<?php
//echo htmlspecialchars($username); 
//php echo htmlspecialchars($gender) . ", " . htmlspecialchars($age) . " Years";
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
    header("Location: register.html");
    exit();
}
?> 

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Home</title>
    <link rel="stylesheet" href="Home.css?v=2">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button id="closeSidebar">&times;</button>
        <div class="user-profile-sidebar">
            <div class="top-profile-sidebar">
                
                <img class="mainProfileImg-sidebar" src="svg/profile.svg" alt="Profile Image">
                <div class="name-age-sidebar">
                    <h2 class="username">
                        Swayam
                    </h2>
                    <p class="gender-age">
                        18 years
                    </p>
                </div>
                
            </div>


            <div class="stats">
                <div class="stat">
                    <span class="label">Height</span>
                    <span>175cm</span>
                    <!-- <span class="value"><?php echo htmlspecialchars($height); ?> cm</span> -->
                </div>
                <div class="separator">|</div>
                <div class="stat">
                    <span class="label">Weight</span>
                    <span>85kg</span>
                    <!-- <span class="value"><?php echo htmlspecialchars($weight); ?> kg</span> -->
                </div>
            </div>

            <!-- <p class="motto">STRONGER EVERY DAY!</p> -->
        </div>
        <ul class="menuList">
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Workouts</a></li>
            <li><a href="#">Meals</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
        <div class="navbar-brand">
            <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
            <div class="mainName">FitTrack</div>
        </div>
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
            <h2 class="username">
                Swayam
            </h2>
            <p class="gender-age">
                18 years male
            </p>

            <div class="stats">
                <div class="stat">
                    <span class="label">Height</span>
                    <!-- <span class="value"><?php echo htmlspecialchars($height); ?> cm</span> -->
                </div>
                <div class="separator">|</div>
                <div class="stat">
                    <span class="label">Weight</span>
                    <!-- <span class="value"><?php echo htmlspecialchars($weight); ?> kg</span> -->
                </div>
            </div>

            <!-- <p class="motto">STRONGER EVERY DAY!</p> -->
        </div>
        <div class="calendar-container">
            <h3 id="calendar-title" class="calendar-title">Calendar</h3>

            <script>
                function updateCalendarTitle() {
                    const titleElement = document.getElementById("calendar-title");
                    const date = new Date();
                    const monthYear = date.toLocaleString("default", { month: "long", year: "numeric" });
                    titleElement.textContent = monthYear;
                }

                // Call the function when the page loads
                updateCalendarTitle();
            </script>
            <div id="calendar" class="calendar"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <!-- <h4>Main Content</h4> -->
        <div class="slideshow-container">
            <div class="slideshow" id="slideshow">
                <div class="card1"><a href="Exercise.html">My Workouts</a></div>
                <div class="card2"><a href="Meal.html">My Meals</a></div>
                <div class="card3">Card 3</div>

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
    <script>
        function generateCalendar() {
            const calendar = document.getElementById('calendar');
            const date = new Date();

            const month = date.getMonth(); // Get current month (0-11)
            const year = date.getFullYear(); // Get current year

            // Get the first day of the month
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0); // Last day of the current month

            const daysInMonth = lastDay.getDate(); // Number of days in the month
            const startDay = firstDay.getDay(); // Day of the week the month starts on

            // Clear the existing calendar
            calendar.innerHTML = '';

            // Add day names (Sun, Mon, Tue, etc.)
            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            daysOfWeek.forEach(day => {
                const dayElement = document.createElement('div');
                dayElement.classList.add('calendar-day');
                dayElement.textContent = day;
                calendar.appendChild(dayElement);
            });

            // Add empty divs for the days before the first day of the month
            for (let i = 0; i < startDay; i++) {
                const emptyDiv = document.createElement('div');
                calendar.appendChild(emptyDiv);
            }

            // Add the days of the month
            for (let i = 1; i <= daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('calendar-day');
                dayDiv.textContent = i;

                // Check if it's today
                const currentDate = new Date();
                if (i === currentDate.getDate() && month === currentDate.getMonth() && year === currentDate.getFullYear()) {
                    dayDiv.classList.add('today');
                }

                calendar.appendChild(dayDiv);
            }
        }

        // Call the generateCalendar function when the page loads
        window.onload = generateCalendar;
        document.addEventListener("DOMContentLoaded", function () {
            const menuIcon = document.getElementById("menuIcon");
            const sidebar = document.getElementById("sidebar");
            const closeSidebar = document.getElementById("closeSidebar");

            menuIcon.addEventListener("click", function () {
                sidebar.style.left = "0"; // Show sidebar
            });

            closeSidebar.addEventListener("click", function () {
                sidebar.style.left = "-450px"; // Hide sidebar
            });

            // Close sidebar when clicking outside
            document.addEventListener("click", function (event) {
                if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                    sidebar.style.left = "-450px";
                }
            });
        });
    </script>

</body>

</html>