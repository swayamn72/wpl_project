<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];

// Get login streak
$sql = "SELECT COUNT(DISTINCT login_date) AS login_streak FROM login_logs WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$login_streak = $row['login_streak'];

$required_days = 30;
$progress = min(100, ($login_streak / $required_days) * 100);
$badge_completed = $login_streak >= $required_days;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitTrack - Achievements</title>
    <link rel="stylesheet" href="meal2.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .progress-container {
            position: relative;
            width: 160px;
            height: 160px;
        }

        .badge-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 60px;
            height: 60px;
            transform: translate(-50%, -50%);
            z-index: 2;
        }

        .progress-ring__background {
            opacity: 0.3;
        }

        .progress-ring__circle {
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            stroke-linecap: round;
        }

        .achievement-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 180px;
        }

        .achievement-card h6 {
            margin-top: 10px;
            font-size: 16px;
        }

        .achievement-card p {
            font-size: 14px;
            color: #666;
        }
    </style>
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
            </div>
        </div>
    </div>
    <ul class="menuList">
        <li><a href="home.php"><img src="svg/home.svg" alt="Home"> Home</a></li>
        <li><a href="meal2.php"><img src="svg/meals.svg" alt="Meals"> Meals</a></li>
        <li><a href="weekexercise.html"><img src="svg/dumbell.svg" alt="Exercises"> Exercises</a></li>
        <li><a href="achievements.php"><img src="svg/medal.svg" alt="Achievements"> Achievements</a></li>
        <li><a href="settings.php"><img src="svg/settings.svg" alt="Settings"> Settings</a></li>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
    <div class="navbar-brand">
        <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
        <div class="mainName">FitTrack - Achievements</div>
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
<div class="content container mt-5 d-flex flex-wrap gap-4">
    <!-- Login Streak Achievement -->
    <div class="achievement-card">
        <div class="progress-container">
            <svg class="progress-ring" width="160" height="160">
                <circle class="progress-ring__background" stroke="#ccc" stroke-width="10" fill="transparent" r="70" cx="80" cy="80"/>
                <circle class="progress-ring__circle" stroke="#3498db" stroke-width="10" fill="transparent" r="70" cx="80" cy="80"
                    stroke-dasharray="<?php echo 2 * M_PI * 70; ?>"
                    stroke-dashoffset="<?php echo 2 * M_PI * 70 * (1 - $progress / 100); ?>"
                    style="transition: stroke-dashoffset 0.5s ease"/>
            </svg>
            <img src="images/streak30.png" alt="Badge" class="badge-icon">
        </div>
        <h6><?php echo $badge_completed ? "✅ Streak Master!" : "30-Day Streak"; ?></h6>
        <p><?php echo $login_streak; ?>/30 logins</p>
    </div>

    <!-- Future achievements can be added here the same way -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.getElementById("menuIcon").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "0";
    });

    document.getElementById("closeSidebar").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "-400px";
    });
</script>
</body>
</html>
