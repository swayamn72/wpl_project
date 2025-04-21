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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitTrack - Sleep</title>
    <link rel="stylesheet" href="meal2.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="health.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-color: #4f46e5;
            --primary-light: #eef2ff;
            --primary-dark: #3730a3;
            --secondary-color: #10b981;
            --secondary-light: #d1fae5;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-gray: #f3f4f6;
            --medium-gray: #e5e7eb;
            --dark-gray: #6b7280;
            --text-dark: #1f2937;
            --text-light: #9ca3af;
            --white: #ffffff;
            --sleep-color: #8b5cf6;
            --sleep-light: #ede9fe;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --transition: all 0.3s ease;
        }
        
        /* Sleep Form */
        .sleep-form-container {
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .sleep-form-container::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(40%, -40%);
        }
        
        .sleep-form-container h3 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .sleep-input {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            border: none;
            padding: 12px 15px;
        }
        
        .sleep-input:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
            border: none;
            outline: none;
        }
        
        .sleep-input-group {
            position: relative;
        }
        
        .sleep-input-group i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 15px;
            color: var(--sleep-color);
        }
        
        .sleep-input-group input {
            padding-left: 40px;
        }
        
        .btn-sleep {
            background-color: white;
            color: var(--sleep-color);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-sleep:hover {
            background-color: var(--sleep-light);
            transform: translateY(-2px);
        }
        
        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-card {
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
        }
        
        .stat-card.purple {
            background-color: var(--sleep-light);
            color: var(--sleep-color);
        }
        
        .stat-card.green {
            background-color: var(--secondary-light);
            color: var(--secondary-color);
        }
        
        .stat-icon {
            font-size: 24px;
            margin-bottom: 12px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Chart Container */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            margin: 30px 0;
        }
        
        .chart-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chart-title i {
            color: var(--sleep-color);
        }
        
        /* Tips Section */
        .tips-section {
            background-color: var(--sleep-light);
            border-radius: 12px;
            padding: 20px;
            margin: 30px 0;
        }
        
        .tips-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--sleep-color);
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .tips-list li {
            margin-bottom: 12px;
            padding-left: 25px;
            position: relative;
        }
        
        .tips-list li:before {
            content: '\f058';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--sleep-color);
            font-size: 14px;
        }
        
        /* Content Styles */
        .content {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            margin-top: 30px;
            margin-bottom: 30px;
        }
        
        .content h2 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 12px;
        }
        
        .content h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 4px;
            background: var(--sleep-color);
            border-radius: 10px;
        }
        
        /* Notification */
        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1000;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }
        
        .notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .notification-icon {
            font-size: 20px;
        }
        
        .notification-success {
            border-left: 4px solid var(--secondary-color);
        }
        
        .notification-success .notification-icon {
            color: var(--secondary-color);
        }
        
        .notification-error {
            border-left: 4px solid var(--danger-color);
        }
        
        .notification-error .notification-icon {
            color: var(--danger-color);
        }
        
        .notification-text {
            font-weight: 500;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
        }
    </style>
</head>

<body>
    <!-- Notification Container -->
    <div id="notification" class="notification">
        <div class="notification-icon"><i class="fas fa-check-circle"></i></div>
        <div class="notification-text">Notification message</div>
    </div>

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
    <nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
        <div class="navbar-brand">
            <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
            <div class="mainName">FitTrack - Sleep</div>
        </div>
        <div class="nav-right">
            <img src="svg/bell.svg" alt="">
            <img src="svg/dark.svg" alt="">
            <form method="post">
                <button type="submit" name="logout" class="btn btn-danger">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="content container animate__animated animate__fadeIn">
        <h2>Sleep Tracker</h2>
        
        <!-- Enhanced Sleep Form -->
        <div class="sleep-form-container animate__animated animate__fadeInUp">
            <h3><i class="fas fa-bed me-2"></i>Record Your Sleep</h3>
            <form action="add_sleep.php" method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="sleep_date" class="form-label">Date</label>
                    <div class="sleep-input-group">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="sleep_date" name="sleep_date" class="form-control sleep-input" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label for="sleep_hours" class="form-label">Hours Slept</label>
                    <div class="sleep-input-group">
                        <i class="fas fa-clock"></i>
                        <input type="number" id="sleep_hours" name="sleep_hours" step="0.1" min="0" max="24" class="form-control sleep-input" placeholder="Hours" required>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-sleep form-control">
                        <i class="fas fa-plus me-2"></i>Add Sleep Record
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card purple animate__animated animate__fadeIn animate__delay-1s">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-value" id="avgSleepHours">-</div>
                <div class="stat-label">Avg. Hours/Night</div>
            </div>
            
            <div class="stat-card green animate__animated animate__fadeIn animate__delay-2s">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value" id="totalRecords">-</div>
                <div class="stat-label">Total Records</div>
            </div>
        </div>
        
        <!-- Sleep Chart -->
        <div class="chart-container">
            <div class="chart-title">
                <i class="fas fa-chart-line"></i>
                Sleep Hours - Last 10 Days
            </div>
            <canvas id="sleepChart" height="300"></canvas>
        </div>
        
        <!-- Tips Section -->
        <div class="tips-section">
            <div class="tips-title">
                <i class="fas fa-lightbulb"></i>
                Sleep Tips for Better Health
            </div>
            <ul class="tips-list">
                <li>Maintain a consistent sleep schedule, even on weekends</li>
                <li>Create a relaxing bedtime routine to signal your body it's time to sleep</li>
                <li>Keep your bedroom cool, dark, and quiet for optimal sleep conditions</li>
                <li>Limit screen time at least an hour before bed</li>
                <li>Avoid caffeine and heavy meals close to bedtime</li>
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Show notification function
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.className = 'notification';
            notification.classList.add(`notification-${type}`);
            
            const icon = notification.querySelector('.notification-icon i');
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
            } else {
                icon.className = 'fas fa-exclamation-circle';
            }
            
            notification.querySelector('.notification-text').textContent = message;
            notification.classList.add('show');
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }
        
        // Auto-fill today's date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('sleep_date').value = today;
            
            // Handle form submission with notification
            const form = document.querySelector('form[action="add_sleep.php"]');
            form.addEventListener('submit', function(e) {
                // Your existing form submission logic works here
                // We're just adding visual feedback
                
                const hours = document.getElementById('sleep_hours').value;
                if (!hours || hours <= 0 || hours > 24) {
                    e.preventDefault();
                    showNotification('Please enter valid sleep hours between 0 and 24', 'error');
                } else {
                    // Form will submit normally
                    // The notification will show on redirect back if you want to add that in your PHP
                }
            });
        });
        
        // Sidebar toggle functionality
        document.getElementById('menuIcon').addEventListener('click', function() {
            document.getElementById('sidebar').style.left = '0';
        });
        
        document.getElementById('closeSidebar').addEventListener('click', function() {
            document.getElementById('sidebar').style.left = '-400px';
        });
        
        // Enhanced chart with better styling
        fetch('fetch_sleep_data.php')
            .then(response => response.json())
            .then(data => {
                // Calculate average sleep hours
                if (data.length > 0) {
                    const totalHours = data.reduce((sum, item) => sum + parseFloat(item.sleep_hours), 0);
                    const avgHours = (totalHours / data.length).toFixed(1);
                    document.getElementById('avgSleepHours').textContent = avgHours;
                    document.getElementById('totalRecords').textContent = data.length;
                }
                
                const labels = data.map(item => {
                    // Format the date to be more readable
                    const date = new Date(item.sleep_date);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                });
                const sleepHours = data.map(item => item.sleep_hours);

                const ctx = document.getElementById('sleepChart').getContext('2d');
                
                // Create gradient for chart
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(139, 92, 246, 0.7)');
                gradient.addColorStop(1, 'rgba(139, 92, 246, 0.1)');
                
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Sleep Hours',
                            data: sleepHours,
                            backgroundColor: gradient,
                            borderColor: '#8b5cf6',
                            borderWidth: 2,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#8b5cf6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                titleColor: '#1f2937',
                                bodyColor: '#1f2937',
                                borderColor: '#e5e7eb',
                                borderWidth: 1,
                                padding: 10,
                                displayColors: false,
                                callbacks: {
                                    title: function(tooltipItems) {
                                        return tooltipItems[0].label;
                                    },
                                    label: function(context) {
                                        return `${context.parsed.y} hours of sleep`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    color: '#6b7280'
                                }
                            },
                            y: {
                                grid: {
                                    color: '#f3f4f6'
                                },
                                ticks: {
                                    font: {
                                        size: 12
                                    },
                                    color: '#6b7280',
                                    callback: function(value) {
                                        return value + 'h';
                                    }
                                },
                                beginAtZero: true,
                                suggestedMax: 10
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading sleep data:', error);
                showNotification('Error loading sleep data', 'error');
            });
    </script>
</body>

</html>