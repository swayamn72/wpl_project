<?php
$host = "localhost"; // Change if using a remote server
$user = "root"; // Default for XAMPP/MAMP
$password = ""; // Default is empty in local setups
$database = "fittrack_db";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
