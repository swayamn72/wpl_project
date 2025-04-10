<?php
header('Content-Type: application/json');
include 'db_connect.php'; // Include database connection

// Ensure user is logged in
session_start();
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION["user_id"];

// Query to fetch both calorie and protein data for the last 10 days
$sql = "
    SELECT DATE(meal_date) AS meal_date, 
           SUM(total_calories) AS daily_calories,
           SUM(total_protein) AS daily_protein
    FROM meals 
    WHERE user_id = ? 
    AND meal_date >= CURDATE() - INTERVAL 9 DAY
    GROUP BY DATE(meal_date)
    ORDER BY meal_date;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    // Convert date format to display correctly
    $row['meal_date'] = date("M d", strtotime($row['meal_date']));
    
    // Ensure numeric values are properly formatted
    $row['daily_calories'] = (float) $row['daily_calories'];
    $row['daily_protein'] = (float) $row['daily_protein'];

    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
