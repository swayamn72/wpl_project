<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT meal_date, meal_name, SUM(total_calories) AS daily_calories 
        FROM meals 
        WHERE user_id = ? 
        AND meal_date >= CURDATE() - INTERVAL 5 DAY
        GROUP BY meal_date, meal_name 
        ORDER BY meal_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$mealData = [];
while ($row = $result->fetch_assoc()) {
    $mealData[] = $row;
}

$stmt->close();
$conn->close();
echo json_encode($mealData);
?>
