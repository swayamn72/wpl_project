<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Error: User not logged in."]);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$mealName = $data["mealName"];
$mealDate = $data["mealDate"];
$items = $data["items"];

// Calculate total calories and protein
$totalCalories = 0;
$totalProtein = 0;

foreach ($items as $item) {
    $totalCalories += floatval($item["calories"]);
    $totalProtein += floatval($item["protein"]);
}

// Check if meal already exists for same user/date/name
$stmt = $conn->prepare("SELECT meal_id FROM meals WHERE user_id = ? AND meal_name = ? AND meal_date = ?");
$stmt->bind_param("iss", $user_id, $mealName, $mealDate);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Meal exists — update
    $stmt->bind_result($meal_id);
    $stmt->fetch();

    // Update totals
    $update = $conn->prepare("UPDATE meals SET total_calories = ?, total_protein = ? WHERE meal_id = ?");
    $update->bind_param("ddi", $totalCalories, $totalProtein, $meal_id);
    $update->execute();
    $update->close();

    // Delete existing meal_items for this meal
    $del = $conn->prepare("DELETE FROM meal_items WHERE meal_id = ?");
    $del->bind_param("i", $meal_id);
    $del->execute();
    $del->close();
} else {
    // Insert new meal
    $insert = $conn->prepare("INSERT INTO meals (user_id, meal_name, meal_date, total_calories, total_protein) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("issdd", $user_id, $mealName, $mealDate, $totalCalories, $totalProtein);
    $insert->execute();
    $meal_id = $insert->insert_id;
    $insert->close();
}

// Insert new meal_items
foreach ($items as $item) {
    $food_name = $item["name"];
    $calories = $item["calories"];
    $protein = $item["protein"];
    $quantity = $item["quantity"];

    // Check or insert food
    $stmt = $conn->prepare("SELECT food_id FROM food_items WHERE name = ?");
    $stmt->bind_param("s", $food_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($food_id);
        $stmt->fetch();
    } else {
        $stmt->close();
        $insertFood = $conn->prepare("INSERT INTO food_items (name, calories_per_100g, protein, serving_size) VALUES (?, ?, ?, 100)");
        $insertFood->bind_param("sdd", $food_name, $calories, $protein);
        $insertFood->execute();
        $food_id = $insertFood->insert_id;
        $insertFood->close();
        continue;
    }

    // Insert into meal_items
    $stmt = $conn->prepare("INSERT INTO meal_items (meal_id, food_id, quantity, actual_serving_size) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iidd", $meal_id, $food_id, $quantity, $quantity);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
echo json_encode(["success" => true, "message" => "Meal saved/updated successfully!"]);
?>
