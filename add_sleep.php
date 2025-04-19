<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION["user_id"];
$sleep_date = $_POST["sleep_date"];
$sleep_hours = $_POST["sleep_hours"];

$sql = "INSERT INTO sleep_logs (user_id, sleep_date, sleep_hours)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE sleep_hours = VALUES(sleep_hours);";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isd", $user_id, $sleep_date, $sleep_hours);

if ($stmt->execute()) {
    header("Location: sleep.php");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
