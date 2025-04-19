<?php
header('Content-Type: application/json');
include 'db_connect.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["error" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "
    SELECT DATE(sleep_date) AS sleep_date, sleep_hours
    FROM sleep_logs
    WHERE user_id = ?
    AND sleep_date >= CURDATE() - INTERVAL 9 DAY
    ORDER BY sleep_date;
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'sleep_date' => date("M d", strtotime($row['sleep_date'])),
        'sleep_hours' => (float) $row['sleep_hours']
    ];
}

echo json_encode($data);
$stmt->close();
$conn->close();
?>
