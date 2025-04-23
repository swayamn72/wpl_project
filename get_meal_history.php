<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Ensure this file establishes a database connection

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch meal history for the last 5 days
$sql = "
    SELECT 
        meal_date, 
        meal_name, 
        SUM(total_calories) AS total_calories
    FROM meals
    WHERE user_id = ? 
      AND meal_date >= CURDATE() - INTERVAL 5 DAY
    GROUP BY meal_date, meal_name
    ORDER BY meal_date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$mealHistory = [];
while ($row = $result->fetch_assoc()) {
    $mealHistory[] = [
        "date" => $row['meal_date'],  // Return raw date string in YYYY-MM-DD format
        "type" => $row['meal_name'],
        "totalCalories" => (float) $row['total_calories'],
    ];
}

$stmt->close();
$conn->close();

echo json_encode($mealHistory);
?>
