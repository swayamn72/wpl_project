<?php
header('Content-Type: application/json');
include 'db_connect.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to get total calories and protein for today's meals
$sql = "
    SELECT 
        COALESCE(SUM(total_calories), 0) AS total_calories,
        COALESCE(SUM(total_protein), 0) AS total_protein
    FROM meals
    WHERE user_id = ?
      AND meal_date = CURDATE()
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = ["total_calories" => 0, "total_protein" => 0];
if ($row = $result->fetch_assoc()) {
    $data["total_calories"] = (float)$row["total_calories"];
    $data["total_protein"] = (float)$row["total_protein"];
}

$stmt->close();
$conn->close();

echo json_encode($data);
?>
