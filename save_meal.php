<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);

$mealName = $data["mealName"];
$mealDate = $data["mealDate"];
$totalCalories = $data["totalCalories"];
$totalProtein = $data["totalProtein"];
$items = $data["items"];

// Insert meal into meals table
$stmt = $conn->prepare("INSERT INTO meals (user_id, meal_name, meal_date, total_calories, total_protein) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issdd", $user_id, $mealName, $mealDate, $totalCalories, $totalProtein);
$stmt->execute();

if ($stmt->affected_rows <= 0) {
    die("Error: Meal insertion failed.");
}

$meal_id = $stmt->insert_id;  // Get the last inserted meal ID

foreach ($items as $item) {
    $food_name = $item["name"];
    $calories = $item["calories"];
    $protein = $item["protein"];
    $quantity = $item["quantity"];

    // Check if food item already exists
    $stmt = $conn->prepare("SELECT food_id FROM food_items WHERE name = ?");
    $stmt->bind_param("s", $food_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($food_id);
        $stmt->fetch();
    } else {
        // Insert new food item
        $stmt = $conn->prepare("INSERT INTO food_items (name, calories_per_100g, protein, serving_size) VALUES (?, ?, ?, 100)");
        $stmt->bind_param("sdd", $food_name, $calories, $protein);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
            die("Error: Food item insertion failed.");
        }

        $food_id = $stmt->insert_id;
    }

    // Insert into meal_items with actual serving size
    $stmt = $conn->prepare("INSERT INTO meal_items (meal_id, food_id, quantity, actual_serving_size) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iidd", $meal_id, $food_id, $quantity, $quantity);
    $stmt->execute();

    if ($stmt->affected_rows <= 0) {
        die("Error: Meal item insertion failed.");
    }
}

$conn->close();
echo "Meal saved successfully!";
?>
