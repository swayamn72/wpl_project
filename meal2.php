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
    <title>FitTrack - Meals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: var(--text-dark);
            min-height: 100vh;
            position: relative;
            padding-bottom: 40px;
        }
        
        /* Navbar Styles */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-md);
            padding: 15px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .menuIcon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .menuIcon:hover {
            background-color: var(--light-gray);
        }
        
        .mainName {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            letter-spacing: 0.5px;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .nav-right img {
            width: 24px;
            height: 24px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .nav-right img:hover {
            opacity: 0.7;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-2px);
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -400px;
            width: 300px;
            height: 100vh;
            background: var(--white);
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            transition: var(--transition);
            overflow-y: auto;
            padding: 20px 0;
        }
        
        #closeSidebar {
            position: absolute;
            top: 15px;
            right: 15px;
            background: transparent;
            border: none;
            font-size: 24px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        
        #closeSidebar:hover {
            background-color: var(--light-gray);
        }
        
        .user-profile-sidebar {
            padding: 20px;
            border-bottom: 1px solid var(--medium-gray);
            margin-bottom: 20px;
        }
        
        .top-profile-sidebar {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .mainProfileImg-sidebar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
        }
        
        .name-age-sidebar h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 0;
        }
        
        .gender-age {
            font-size: 14px;
            color: var(--text-light);
            margin-bottom: 0;
        }
        
        .menuList {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .menuList li {
            margin-bottom: 5px;
        }
        
        .menuList li a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 25px;
            text-decoration: none;
            color: var(--text-dark);
            transition: var(--transition);
            font-weight: 500;
        }
        
        .menuList li a:hover {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }
        
        .menuList li a img {
            width: 20px;
            height: 20px;
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
            background: var(--primary-color);
            border-radius: 10px;
        }
        
        label {
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: var(--text-dark);
        }
        
        .form-select, .form-control {
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
            padding: 12px 15px;
            transition: var(--transition);
        }
        
        .form-select:focus, .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-2px);
        }
        
        /* Meal Cards */
        .mealcard {
            border-radius: 12px;
            border: 1px solid var(--medium-gray);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            padding: 20px !important;
            margin-bottom: 15px !important;
            position: relative;
            overflow: hidden;
        }
        
        .mealcard:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }
        
        .mealcard h5 {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 15px;
            text-transform: capitalize;
        }
        
        .mealcard p {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            color: var(--text-dark);
        }
        
        .mealcard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--primary-color);
            border-radius: 4px 0 0 4px;
        }
        
        .serving-input {
            border-radius: 8px;
            margin: 10px 0;
            width: 100%;
        }
        
        .delete-btn {
            background-color: #fee2e2;
            color: var(--danger-color);
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .delete-btn:hover {
            background-color: var(--danger-color);
            color: white;
        }
        
        /* Stats Area */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
        
        .stat-card.calories {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .stat-card.protein {
            background-color: #dbeafe;
            color: #1e40af;
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
        
        /* Table Styles */
        .table-container {
            margin: 30px 0;
            box-shadow: var(--shadow-sm);
            border-radius: 12px;
            overflow: hidden;
        }
        
        .table {
            margin-bottom: 0;
            border-collapse: collapse;
        }
        
        .table th {
            background-color: var(--primary-light);
            color: var(--primary-color);
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: none;
            padding: 15px;
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
            color: var(--text-dark);
            border-color: var(--medium-gray);
        }
        
        .table tbody tr {
            transition: var(--transition);
        }
        
        .table tbody tr:hover {
            background-color: var(--light-gray);
        }
        
        /* Search Form */
        .search-area {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .search-area::after {
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
        
        .search-area h3 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .search-form-wrapper {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .search-form-wrapper select,
        .search-form-wrapper .search-input-group {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            border: none;
        }
        
        .search-input-group {
            display: flex;
            overflow: hidden;
        }
        
        .search-input-group input {
            flex-grow: 1;
            border: none;
            border-radius: 8px 0 0 8px;
            padding: 12px 15px;
        }
        
        .search-input-group input:focus {
            outline: none;
            box-shadow: none;
        }
        
        .search-input-group button {
            border-radius: 0 8px 8px 0;
            padding: 0 20px;
            background-color: white;
            color: var(--primary-color);
            border: none;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .search-input-group button:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Empty State */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
            color: var(--text-light);
        }
        
        .empty-icon {
            font-size: 48px;
            margin-bottom: 15px;
            color: var(--medium-gray);
        }
        
        /* Progress Bar */
        .progress-container {
            margin: 20px 0;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .progress-bar-outer {
            height: 8px;
            background-color: var(--light-gray);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-bar-inner {
            height: 100%;
            background: linear-gradient(to right, #4f46e5, #2563eb);
            border-radius: 4px;
            transition: width 0.5s ease;
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .scale-in {
            animation: scaleIn 0.3s ease-in-out;
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
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
            
            .search-input-group {
                flex-direction: column;
            }
            
            .search-input-group input {
                border-radius: 8px 8px 0 0;
            }
            
            .search-input-group button {
                border-radius: 0 0 8px 8px;
                padding: 12px;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
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
    <button id="closeSidebar"><i class="fas fa-times"></i></button>
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
        <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="meal2.php"><i class="fas fa-utensils"></i> Meals</a></li>
        <li><a href="weekexercise.html"><i class="fas fa-dumbbell"></i> Exercises</a></li>
        <li><a href="achievements.php"><i class="fas fa-medal"></i> Achievements</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar d-flex justify-content-between align-items-center animate__animated animate__fadeIn">
    <div class="navbar-brand">
        <div class="menuIcon" id="menuIcon"><i class="fas fa-bars"></i></div>
        <div class="mainName">FitTrack - Nutrition</div>
    </div>
    <div class="nav-right">
        <i class="fas fa-bell"></i>
        <i class="fas fa-moon"></i>
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>
</nav>

<!-- Main Content -->
<div class="content container animate__animated animate__fadeIn">
    <h2>Track Your Meals</h2>
    
    <div class="search-area animate__animated animate__fadeInUp">
        <h3>Add Food to Your Plan</h3>
        <div class="search-form-wrapper">
            <select id="mealType" class="form-select">
                <option value="Breakfast">Breakfast</option>
                <option value="Lunch">Lunch</option>
                <option value="Snacks">Snacks</option>
                <option value="Dinner">Dinner</option>
            </select>
            
            <form id="searchForm" class="d-flex gap-2">
                <div class="search-input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for food (e.g., apple, chicken breast, rice)">
                    <button type="submit">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="stats-container">
        <div class="stat-card calories animate__animated animate__fadeIn animate__delay-1s">
            <div class="stat-icon"><i class="fas fa-fire"></i></div>
            <div class="stat-value" id="totalCalories">0</div>
            <div class="stat-label">Calories (kcal)</div>
        </div>
        
        <div class="stat-card protein animate__animated animate__fadeIn animate__delay-2s">
            <div class="stat-icon"><i class="fas fa-drumstick-bite"></i></div>
            <div class="stat-value" id="totalProtein">0</div>
            <div class="stat-label">Protein (g)</div>
        </div>
    </div>
    
    <div class="progress-container">
        <div class="progress-label">
            <span>Daily Calorie Goal</span>
            <span id="calorieProgress">0/2000 kcal</span>
        </div>
        <div class="progress-bar-outer">
            <div class="progress-bar-inner" id="calorieProgressBar" style="width: 0%"></div>
        </div>
    </div>
    
    <h4 class="mt-4 mb-3">
        <i class="fas fa-clipboard-list me-2"></i>
        Selected Foods
    </h4>
    
    <div id="info" class="mb-3">
        <div class="empty-state" id="emptyState">
            <div class="empty-icon"><i class="fas fa-utensils"></i></div>
            <h5>No foods added yet</h5>
            <p>Search for food items above to add them to your meal plan</p>
        </div>
    </div>
    
    <button id="saveMeal" class="btn btn-success w-100 mb-4">
        <i class="fas fa-save me-2"></i>Save Meal
    </button>
    
    <h4 class="mt-4">
        <i class="fas fa-history me-2"></i>
        Recent Meal History
    </h4>
    <p class="text-muted">Last 5 days of meal tracking</p>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Meal</th>
                    <th>Calories</th>
                </tr>
            </thead>
            <tbody id="mealHistory"></tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let mealItems = [];
const calorieGoal = 2000; // Default daily calorie goal

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

function updateTotals() {
    const totalCalories = mealItems.reduce((sum, i) => sum + parseFloat(i.calories || 0), 0);
    const totalProtein = mealItems.reduce((sum, i) => sum + parseFloat(i.protein || 0), 0);
    
    document.getElementById("totalCalories").textContent = totalCalories.toFixed(0);
    document.getElementById("totalProtein").textContent = totalProtein.toFixed(1);
    
    // Update progress bar
    const percentComplete = Math.min((totalCalories / calorieGoal) * 100, 100);
    document.getElementById("calorieProgressBar").style.width = `${percentComplete}%`;
    document.getElementById("calorieProgress").textContent = `${totalCalories.toFixed(0)}/${calorieGoal} kcal`;
    
    // Change progress bar color based on completion
    const progressBar = document.getElementById("calorieProgressBar");
    if (percentComplete > 85) {
        progressBar.style.background = 'linear-gradient(to right, #f87171, #ef4444)';
    } else if (percentComplete > 60) {
        progressBar.style.background = 'linear-gradient(to right, #fbbf24, #f59e0b)';
    } else {
        progressBar.style.background = 'linear-gradient(to right, #4f46e5, #2563eb)';
    }
    
    // Show/hide empty state
    document.getElementById('emptyState').style.display = mealItems.length === 0 ? 'block' : 'none';
}

document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let query = document.getElementById("searchInput").value.trim();
    
    if (!query) {
        showNotification('Please enter a food to search', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
    submitBtn.disabled = true;
    
    $.ajax({
        method: 'GET',
        url: 'https://api.calorieninjas.com/v1/nutrition?query=' + query,
        headers: { 'X-Api-Key': '1MjVyE4++leUa2iRMXPiOQ==aZSLs6REOYYpkFTj' },
        success: function(result) {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
            if (!result.items || result.items.length === 0) {
                showNotification('No food items found', 'error');
                return;
            }
            
            let infoDiv = document.getElementById("info");
            
            // Remove empty state if this is the first item
            if (mealItems.length === 0) {
                infoDiv.innerHTML = '';
            }
            
            result.items.forEach(item => {
                const food = {
                    name: item.name,
                    calories: item.calories,
                    protein: item.protein_g,
                    quantity: 100,
                    caloriesPerGram: item.calories / 100,
                    proteinPerGram: item.protein_g / 100
                };
                
                // Check if item already exists
                const existingItem = mealItems.find(i => i.name === food.name);
                if (existingItem) {
                    showNotification(`${food.name} is already in your meal`, 'error');
                    return;
                }
                
                mealItems.push(food);
                updateTotals();

                let card = document.createElement("div");
                card.className = "mealcard p-3 rounded mb-3 bg-white scale-in";
                card.innerHTML = `
                    <h5>${food.name}</h5>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p><strong>Calories:</strong> <span class="cal">${food.calories}</span> kcal</p>
                            <p><strong>Protein:</strong> <span class="prot">${food.protein}</span> g</p>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Serving (g):</label>
                               <input type="number" class="form-control serving-input" value="100" min="1" max="1000">
                            </div>
                        </div>
                    </div>
                    <button class="delete-btn mt-2">
                        <i class="fas fa-trash-alt"></i> Remove
                    </button>
                `;
                
                // Add the card to the info div
                infoDiv.appendChild(card);
                
                // Add event listener to the serving input
                const servingInput = card.querySelector('.serving-input');
                servingInput.addEventListener('change', function() {
                    const index = mealItems.findIndex(i => i.name === food.name);
                    if (index !== -1) {
                        const quantity = parseFloat(this.value);
                        mealItems[index].quantity = quantity;
                        mealItems[index].calories = quantity * mealItems[index].caloriesPerGram;
                        mealItems[index].protein = quantity * mealItems[index].proteinPerGram;
                        
                        // Update displayed values
                        card.querySelector('.cal').textContent = mealItems[index].calories.toFixed(0);
                        card.querySelector('.prot').textContent = mealItems[index].protein.toFixed(1);
                        
                        updateTotals();
                    }
                });
                
                // Add event listener to the delete button
                const deleteBtn = card.querySelector('.delete-btn');
                deleteBtn.addEventListener('click', function() {
                    const index = mealItems.findIndex(i => i.name === food.name);
                    if (index !== -1) {
                        mealItems.splice(index, 1);
                        card.classList.add('animate__animated', 'animate__fadeOutRight');
                        setTimeout(() => {
                            card.remove();
                            updateTotals();
                        }, 300);
                    }
                });
                
                // Clear search input
                document.getElementById("searchInput").value = '';
                showNotification(`${food.name} added to your meal`);
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Reset button
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            showNotification('Error searching for food. Please try again.', 'error');
            console.error('Error: ', textStatus, errorThrown);
        }
    });
});

// Save meal functionality
document.getElementById("saveMeal").addEventListener("click", function() {
    if (mealItems.length === 0) {
        showNotification('No food items to save', 'error');
        return;
    }
    
    const mealType = document.getElementById("mealType").value;
    const mealData = {
        type: mealType,
        items: mealItems,
        date: new Date().toISOString().split('T')[0], // YYYY-MM-DD format
        totalCalories: parseFloat(document.getElementById("totalCalories").textContent),
        totalProtein: parseFloat(document.getElementById("totalProtein").textContent)
    };
    
    // Show loading state
    const originalText = this.innerHTML;
    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    this.disabled = true;
    
    // Save meal data to server
    $.ajax({
        url: 'save_meal.php',
        type: 'POST',
        data: JSON.stringify(mealData),
        contentType: 'application/json',
        success: function(response) {
            // Reset button
            document.getElementById("saveMeal").innerHTML = originalText;
            document.getElementById("saveMeal").disabled = false;
            
            try {
                const result = JSON.parse(response);
                if (result.success) {
                    // Clear meal items
                    mealItems = [];
                    document.getElementById("info").innerHTML = `
                        <div class="empty-state" id="emptyState">
                            <div class="empty-icon"><i class="fas fa-utensils"></i></div>
                            <h5>No foods added yet</h5>
                            <p>Search for food items above to add them to your meal plan</p>
                        </div>
                    `;
                    updateTotals();
                    showNotification('Meal saved successfully!');
                    loadMealHistory(); // Reload meal history
                } else {
                    showNotification(result.message || 'Error saving meal', 'error');
                }
            } catch (e) {
                showNotification('Error processing server response', 'error');
            }
        },
        error: function() {
            // Reset button
            document.getElementById("saveMeal").innerHTML = originalText;
            document.getElementById("saveMeal").disabled = false;
            showNotification('Error saving meal. Please try again.', 'error');
        }
    });
});

// Load meal history from server
function loadMealHistory() {
    $.ajax({
        url: 'get_meal_history.php',
        type: 'GET',
        success: function(response) {
            try {
                const history = JSON.parse(response);
                const historyTable = document.getElementById('mealHistory');
                historyTable.innerHTML = '';
                
                if (history.length === 0) {
                    historyTable.innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center py-4">No meal history available</td>
                        </tr>
                    `;
                    return;
                }
                
                history.forEach(meal => {
                    const row = document.createElement('tr');
                    const date = new Date(meal.date);
                    const formattedDate = date.toLocaleDateString('en-US', { 
                        weekday: 'short', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                    
                    row.innerHTML = `
                        <td>${formattedDate}</td>
                        <td>${meal.type}</td>
                        <td>${Math.round(meal.totalCalories)} kcal</td>
                    `;
                    historyTable.appendChild(row);
                });
            } catch (e) {
                console.error('Error parsing meal history:', e);
            }
        },
        error: function() {
            console.error('Error loading meal history');
        }
    });
}

// Toggle sidebar
document.getElementById("menuIcon").addEventListener("click", function() {
    document.getElementById("sidebar").style.left = "0";
});

document.getElementById("closeSidebar").addEventListener("click", function() {
    document.getElementById("sidebar").style.left = "-400px";
});

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    updateTotals();
    loadMealHistory();
});
</script>

</body>
</html>