<?php
session_start();
require 'db_connect.php'; // include DB connection

if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}

$username = $_SESSION["username"];
$errors = [];
$success = "";

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newUsername = trim($_POST["new_username"]);
    $email = trim($_POST["email"]);
    $age = (int)$_POST["age"];
    $weight = (float)$_POST["weight"];
    $height = (float)$_POST["height"];

    // Validate password change
    if (!empty($_POST["new_password"])) {
        if ($_POST["new_password"] !== $_POST["confirm_password"]) {
            $errors[] = "Passwords do not match.";
        } else {
            $hashedPassword = password_hash($_POST["new_password"], PASSWORD_DEFAULT);
        }
    } else {
        $hashedPassword = $user['password']; // keep existing
    }

    // Check for errors before updating
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=?, age=?, weight=?, height=? WHERE user_id=?");
        $stmt->bind_param("sssiddi", $newUsername, $email, $hashedPassword, $age, $weight, $height, $user['user_id']);
        if ($stmt->execute()) {
            $_SESSION["username"] = $newUsername;
            $_SESSION["age"] = $age;
            $success = "Profile updated successfully!";
        } else {
            $errors[] = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - FitTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="settings.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="meal2.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <button id="closeSidebar">&times;</button>
    <div class="user-profile-sidebar">
        <img class="mainProfileImg-sidebar" src="svg/profile.svg" alt="Profile Image">
        <div class="name-age-sidebar">
            <h2 class="username"><?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
            <p class="gender-age"><?php echo htmlspecialchars($_SESSION["age"]); ?> years old</p>
        </div>
    </div>
    <ul class="menuList">
        <li><a href="home.php"><img src="svg/home.svg" alt=""> Home</a></li>
        <li><a href="meal2.php"><img src="svg/meals.svg" alt=""> Meals</a></li>
        <li><a href="weekexercise.html"><img src="svg/dumbell.svg" alt=""> Exercises</a></li>
        <li><a href="achievements.html"><img src="svg/medal.svg" alt=""> Achievements</a></li>
        <li><a href="settings.php"><img src="svg/settings.svg" alt=""> Settings</a></li>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between">
    <div class="navbar-brand d-flex align-items-center">
        <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
        <div class="mainName ms-2">FitTrack - Settings</div>
    </div>
    <form method="post" action="logout.php">
        <button type="submit" class="btn btn-danger">Logout</button>
    </form>
</nav>

<!-- Main Content -->
<div class="container mt-4">
    <h3 class="mb-4">Account Settings</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger"><?php echo implode("<br>", $errors); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="post" class="row g-3">
        <div class="col-md-6">
            <label for="new_username" class="form-label">Username</label>
            <input type="text" class="form-control" name="new_username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="col-md-4">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" min="0" required>
        </div>
        <div class="col-md-4">
            <label for="weight" class="form-label">Weight (kg)</label>
            <input type="number" step="0.1" class="form-control" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>">
        </div>
        <div class="col-md-4">
            <label for="height" class="form-label">Height (cm)</label>
            <input type="number" step="0.1" class="form-control" name="height" value="<?php echo htmlspecialchars($user['height']); ?>">
        </div>
        <div class="col-md-6">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" name="new_password" placeholder="Leave blank to keep current password">
        </div>
        <div class="col-md-6">
            <label for="confirm_password" class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" name="confirm_password">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>

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
