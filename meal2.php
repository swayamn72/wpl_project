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
    <link rel="stylesheet" href="meal3.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        
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
    <div class="progress-container mt-3">
        <div class="progress-label">
            <span>Daily Protein Goal</span>
            <span id="proteinProgress">0/100 g</span>
        </div>
        <div class="progress-bar-outer">
            <div class="progress-bar-inner" id="proteinProgressBar" style="width: 0%; background: linear-gradient(to right, #34d399, #059669);"></div>
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
const calorieGoal = 2000; 
const proteinGoal = 100;

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
    
    // Update calorie progress bar
    const caloriePercent = Math.min((totalCalories / calorieGoal) * 100, 100);
    const calorieProgressBar = document.getElementById("calorieProgressBar");
    calorieProgressBar.style.width = `${caloriePercent}%`;
    document.getElementById("calorieProgress").textContent = `${totalCalories.toFixed(0)}/${calorieGoal} kcal`;
    
    // Change calorie progress bar color based on completion
    if (caloriePercent > 85) {
        calorieProgressBar.style.background = 'linear-gradient(to right, #f87171, #ef4444)';
    } else if (caloriePercent > 60) {
        calorieProgressBar.style.background = 'linear-gradient(to right, #fbbf24, #f59e0b)';
    } else {
        calorieProgressBar.style.background = 'linear-gradient(to right, #4f46e5, #2563eb)';
    }
    
    // Update protein progress bar
    const proteinPercent = Math.min((totalProtein / proteinGoal) * 100, 100);
    const proteinProgressBar = document.getElementById("proteinProgressBar");
    proteinProgressBar.style.width = `${proteinPercent}%`;
    document.getElementById("proteinProgress").textContent = `${totalProtein.toFixed(1)}/${proteinGoal} g`;
    
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
        mealName: mealType,
        items: mealItems,
        mealDate: new Date().toISOString().split('T')[0], // YYYY-MM-DD format
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
                    showNotification(result.message || 'Meal saved successfully!');
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
            console.log("Meal history response:", response);
            try {
                const history = response; // response is already parsed object
                console.log("Parsed meal history:", history);
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
    // Fetch today's nutrition totals and update progress bars
    fetchTodayNutrition();
});

// Fetch today's nutrition totals from server
function fetchTodayNutrition() {
    $.ajax({
        url: 'get_today_nutrition.php',
        type: 'GET',
        success: function(response) {
            if (response.error) {
                console.error('Error fetching today nutrition:', response.error);
                return;
            }
            const totalCalories = response.total_calories || 0;
            const totalProtein = response.total_protein || 0;
            
            // Update calorie progress bar
            const caloriePercent = Math.min((totalCalories / calorieGoal) * 100, 100);
            const calorieProgressBar = document.getElementById("calorieProgressBar");
            calorieProgressBar.style.width = `${caloriePercent}%`;
            document.getElementById("calorieProgress").textContent = `${totalCalories.toFixed(0)}/${calorieGoal} kcal`;
            
            // Change calorie progress bar color based on completion
            if (caloriePercent > 85) {
                calorieProgressBar.style.background = 'linear-gradient(to right, #f87171, #ef4444)';
            } else if (caloriePercent > 60) {
                calorieProgressBar.style.background = 'linear-gradient(to right, #fbbf24, #f59e0b)';
            } else {
                calorieProgressBar.style.background = 'linear-gradient(to right, #4f46e5, #2563eb)';
            }
            
            // Update protein progress bar
            const proteinPercent = Math.min((totalProtein / proteinGoal) * 100, 100);
            const proteinProgressBar = document.getElementById("proteinProgressBar");
            proteinProgressBar.style.width = `${proteinPercent}%`;
            document.getElementById("proteinProgress").textContent = `${totalProtein.toFixed(1)}/${proteinGoal} g`;
        },
        error: function() {
            console.error('Error fetching today nutrition data');
        }
    });
}
</script>

</body>
</html>