<?php
require "db_connect.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        die("User not logged in.");
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $_SESSION["user_id"];
    $meal_name = $data["mealName"];
    $meal_date = date("Y-m-d"); // Automatically set current date
    $total_calories = $data["totalCalories"];
    $items = $data["items"];

    // Insert meal into `meals` table
    $stmt = $conn->prepare("INSERT INTO meals (user_id, meal_name, meal_date, total_calories) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("issd", $user_id, $meal_name, $meal_date, $total_calories);
    
    if (!$stmt->execute()) {
        die("Error inserting meal: " . $stmt->error);
    }

    $meal_id = $stmt->insert_id;
    $stmt->close();

    // Insert food items into `food_items` and `meal_items`
    foreach ($items as $item) {
        $name = $item["name"];
        $calories = $item["calories"];
        $protein = $item["protein"];
        $quantity = $item["quantity"];

        $stmt = $conn->prepare("INSERT INTO food_items (name, calories_per_100g, protein, serving_size) VALUES (?, ?, ?, 100) ON DUPLICATE KEY UPDATE food_id=LAST_INSERT_ID(food_id)");
        $stmt->bind_param("sdd", $name, $calories, $protein);
        $stmt->execute();
        $food_id = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO meal_items (meal_id, food_id, quantity, actual_serving_size) VALUES (?, ?, ?, 100)");
        $stmt->bind_param("iii", $meal_id, $food_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
    echo "Meal saved successfully!";
}
?>
