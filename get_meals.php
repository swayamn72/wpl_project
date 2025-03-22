<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT meal_date, meal_name, total_calories FROM meals WHERE user_id = ? ORDER BY meal_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$meals = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meals .= "<tr>
                      <td>{$row['meal_date']}</td>
                      <td>{$row['meal_name']}</td>
                      <td>{$row['total_calories']} kcal</td>
                   </tr>";
    }
} else {
    $meals = "<tr><td colspan='3'>No meals found</td></tr>";
}

$stmt->close();
$conn->close();
echo $meals;
?>
