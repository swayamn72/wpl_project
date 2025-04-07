<?php
session_start(); // Ensure session starts before any output

// Debug: Check if session is working
if (!isset($_SESSION["username"])) {
    header("Location: login.html"); // Redirect if not logged in
    exit();
}

// Store session variables
$username = $_SESSION['username'] ?? 'Guest';
$gender = $_SESSION['gender'] ?? 'Unknown';
$age = $_SESSION['age'] ?? 0;

// Logout logic
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
    <link rel="stylesheet" href="Home.css?v=<?php echo time(); ?>">

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
                    <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
                    <p class="gender-age"><?php echo htmlspecialchars($age); ?> years old</p>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu List -->
        <ul class="menuList">
            <li>
                <a href="home.php">
                    <img src="svg/home.svg" alt="Home"> Home
                </a>
            </li>
            <li>
                <a href="Meal.html">
                    <img src="svg/meals.svg" alt="Meals"> Meals
                </a>
            </li>
            <li>
                <a href="weekexercise.html">
                    <img src="svg/dumbell.svg" alt="Exercises"> Exercises
                </a>
            </li>
            <li>
                <a href="achievements.html">
                    <img src="svg/medal.svg" alt="Achievements"> Achievements
                </a>
            </li>
            <li>
                <a href="settings.html">
                    <img src="svg/settings.svg" alt="Settings"> Settings
                </a>
            </li>
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
        <div class="nav-right">
            <img src="svg/bell.svg" alt="">
            <img src="svg/dark.svg" alt="">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>

    </nav>



    <!-- Sidebar -->
    <div class="left-bar">
        <div class="user-profile">
            <img class="mainProfileImg" src="svg/profile.svg" alt="Profile Image">
            <h2 class="username">
                <?php  echo htmlspecialchars($username); ?>
            </h2>
            <p class="gender-age">
                <?php echo htmlspecialchars($age); ?> years old
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
                const monthYear = date.toLocaleString("default", {
                    month: "long",
                    year: "numeric"
                });
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
            <div class="slideshowButton">
                <img src="svg/back.svg" class="slideshowBack">
                <img src="svg/next.svg" class="slideshowNext" alt="">
            </div>
            <div class="slideshow" id="slideshow">
                <div class="card1"><a href="weekexercise.html">My Workouts</a></div>
                <div class="card2"><a href="Meal.html">My Meals</a></div>
                <div class="card3">Card 3</div>
                <div class="card4">Card 4</div>
                <div class="card5">Card 5</div>

            </div>
        </div>
        <div class="graphContainer">
            <div class="graphHeading">
                <h4>Progress Graph</h4>
                <div class="graphBtn">
                    <button class="graphBtnCommon graphBtnExercise"><span></span>Exercise</button>
                    <button class="graphBtnCommon graphBtnMeals"><span></span>Meals</button>
                    <button class="graphBtnCommon graphBtnSleep"><span></span>Sleep</button>
                </div>
            </div>
            <div class="actualGraph">
                <canvas id="mealGraph" width="400" height="200"></canvas>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // Date Selection
    function formatDate(date) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return date.toLocaleDateString('en-US', options);
    }

    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date();
        document.getElementById("selected-date").textContent = `Today, ${formatDate(today)}`;

        flatpickr("#selected-date", {
            onChange: function(selectedDates, dateStr) {
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
    document.addEventListener("DOMContentLoaded", function() {
        const menuIcon = document.getElementById("menuIcon");
        const sidebar = document.getElementById("sidebar");
        const closeSidebar = document.getElementById("closeSidebar");

        menuIcon.addEventListener("click", function() {
            sidebar.style.left = "0"; // Show sidebar
        });

        closeSidebar.addEventListener("click", function() {
            sidebar.style.left = "-450px"; // Hide sidebar
        });

        // Close sidebar when clicking outside
        document.addEventListener("click", function(event) {
            if (!sidebar.contains(event.target) && !menuIcon.contains(event.target)) {
                sidebar.style.left = "-450px";
            }
        });
    });
    </script>\
    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch("fetch_meal_data.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }

            console.log("Fetched Meal Data:", data);

            const labels = data.map(item => item.meal_date);
            const calories = data.map(item => item.daily_calories);

            const ctx = document.getElementById("mealGraph").getContext("2d");

            if (!ctx) {
                console.error("Canvas element not found!");
                return;
            }

            // Create a gradient for a better visual effect
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, "rgba(255, 0, 0, 0.5)");
            gradient.addColorStop(1, "rgba(255, 0, 0, 0.1)");

            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Calories Intake",
                        data: calories,
                        borderColor: "red",
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointRadius: 5,  // Bigger dots for better visibility
                        pointBackgroundColor: "red",
                        pointBorderColor: "#fff",
                        pointHoverRadius: 8,
                        tension: 0.3, // Smooth curve effect
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: Math.min(...calories) - 200, // Adjust minimum dynamically
                            grid: {
                                color: "rgba(200, 200, 200, 0.2)" // Light grid lines
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false // Hide X-axis grid lines for a cleaner look
                            },
                            ticks: {
                                font: {
                                    size: 14
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: "top",
                            labels: {
                                font: {
                                    size: 14,
                                    weight: "bold"
                                },
                                color: "black"
                            }
                        }
                    }
                }
            });

            console.log("Chart updated with better design!");
        })
        .catch(error => console.error("Error fetching meal data:", error));
});
</script>


</body>

</html>