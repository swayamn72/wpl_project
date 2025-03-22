<?php
include 'db_connect.php';
session_start(); // Start session to access logged-in user

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id']; // Get user ID from session
$data = json_decode(file_get_contents("php://input"), true);

$mealName = $data["mealName"];
$mealDate = $data["mealDate"];
$totalCalories = $data["totalCalories"];
$items = $data["items"];

// Check if the user exists in the database before inserting
$stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("Error: User does not exist.");
}

// Insert the meal
$stmt = $conn->prepare("INSERT INTO meals (user_id, meal_name, meal_date, total_calories) VALUES (?, ?, ?, ?)");
$stmt->bind_param("issd", $user_id, $mealName, $mealDate, $totalCalories);
$stmt->execute();
$meal_id = $stmt->insert_id;

foreach ($items as $item) {
    $food_name = $item["name"];
    $calories = $item["calories"];
    $protein = $item["protein"];

    // Check if food exists
    $stmt = $conn->prepare("SELECT food_id FROM food_items WHERE name = ?");
    $stmt->bind_param("s", $food_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($food_id);
        $stmt->fetch();
    } else {
        // Insert new food
        $stmt = $conn->prepare("INSERT INTO food_items (name, calories_per_100g, protein, serving_size) VALUES (?, ?, ?, 100)");
        $stmt->bind_param("sdd", $food_name, $calories, $protein);
        $stmt->execute();
        $food_id = $stmt->insert_id;
    }

    // Insert into meal_items
    $stmt = $conn->prepare("INSERT INTO meal_items (meal_id, food_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $meal_id, $food_id, $item["quantity"]);
    $stmt->execute();
}

$conn->close();
echo "Meal saved successfully!";
?>
