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
    <link rel="stylesheet" href="meal2.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <div class="mainName">FitTrack - Meals</div>
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
    <h2>Track Your Meals</h2>

    <label for="mealType">Select Meal:</label>
    <select id="mealType" class="form-select w-50 mb-3">
        <option value="Breakfast">Breakfast</option>
        <option value="Lunch">Lunch</option>
        <option value="Snacks">Snacks</option>
        <option value="Dinner">Dinner</option>
    </select>

    <form id="searchForm" class="d-flex gap-2 mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="Enter Food Name">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <div id="info" class="mb-3"></div>

    <button id="saveMeal" class="btn btn-success mb-4">Save Meal</button>

    <h4>Your Daily Intake</h4>
    <p><strong>Calories:</strong> <span id="totalCalories">0</span> kcal</p>
    <p><strong>Protein:</strong> <span id="totalProtein">0</span> g</p>

    <h4 class="mt-5">Recent Meal History (Last 5 Days)</h4>
    <table class="table table-bordered mt-2">
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let mealItems = [];

function updateTotals() {
    const totalCalories = mealItems.reduce((sum, i) => sum + parseFloat(i.calories || 0), 0);
    const totalProtein = mealItems.reduce((sum, i) => sum + parseFloat(i.protein || 0), 0);
    document.getElementById("totalCalories").textContent = totalCalories.toFixed(2);
    document.getElementById("totalProtein").textContent = totalProtein.toFixed(2);
}

document.getElementById("searchForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let query = document.getElementById("searchInput").value;
    $.ajax({
        method: 'GET',
        url: 'https://api.calorieninjas.com/v1/nutrition?query=' + query,
        headers: { 'X-Api-Key': '1MjVyE4++leUa2iRMXPiOQ==aZSLs6REOYYpkFTj' },
        success: function(result) {
            let infoDiv = document.getElementById("info");
            result.items.forEach(item => {
                const food = {
                    name: item.name,
                    calories: item.calories,
                    protein: item.protein_g,
                    quantity: 100,
                    caloriesPerGram: item.calories / 100,
                    proteinPerGram: item.protein_g / 100
                };
                mealItems.push(food);
                updateTotals();

                let card = document.createElement("div");
                card.className = "mealcard p-3 border rounded mb-2 bg-white";
                card.innerHTML = `
                    <h5>${food.name}</h5>
                    <p>Calories: <span class="cal">${food.calories}</span> kcal</p>
                    <p>Protein: <span class="prot">${food.protein}</span> g</p>
                    <input type="number" class="form-control mb-2 serving-input" value="100" min="1" data-name="${food.name}">
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                `;

                card.querySelector(".delete-btn").addEventListener("click", () => {
                    card.remove();
                    mealItems = mealItems.filter(i => i.name !== food.name);
                    updateTotals();
                });

                card.querySelector(".serving-input").addEventListener("input", function() {
                    let newVal = parseFloat(this.value);
                    let f = mealItems.find(i => i.name === this.dataset.name);
                    if (f) {
                        f.quantity = newVal;
                        f.calories = (f.caloriesPerGram * newVal).toFixed(2);
                        f.protein = (f.proteinPerGram * newVal).toFixed(2);
                        card.querySelector(".cal").textContent = f.calories;
                        card.querySelector(".prot").textContent = f.protein;
                        updateTotals();
                    }
                });

                infoDiv.appendChild(card);
            });
        }
    });
});

document.getElementById("saveMeal").addEventListener("click", function() {
    let mealName = document.getElementById("mealType").value;
    let mealDate = new Date().toISOString().split('T')[0];
    if (mealItems.length === 0) return alert("Add food first");

    $.ajax({
        method: 'POST',
        url: 'save_meal.php',
        contentType: 'application/json',
        data: JSON.stringify({ mealName, mealDate, items: mealItems }),
        success: function(response) {
            alert("Meal saved!");
            mealItems = [];
            document.getElementById("info").innerHTML = "";
            updateTotals();
            loadHistory();
        }
    });
});

function loadHistory() {
    $.get("get_meals.php", function(data) {
        let meals = JSON.parse(data);
        let lastFive = meals.slice(-5);
        let tbody = document.getElementById("mealHistory");
        tbody.innerHTML = "";
        lastFive.forEach(meal => {
            tbody.innerHTML += `
                <tr>
                    <td>${meal.meal_date}</td>
                    <td>${meal.meal_name}</td>
                    <td>${parseFloat(meal.daily_calories).toFixed(2)} kcal</td>
                </tr>`;
        });
    });
}

window.onload = function() {
    loadHistory();

    document.getElementById("menuIcon").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "0";
    });

    document.getElementById("closeSidebar").addEventListener("click", () => {
        document.getElementById("sidebar").style.left = "-400px";
    });
};
</script>
</body>
</html>
