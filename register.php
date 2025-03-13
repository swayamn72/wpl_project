<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Consider using password_hash($password, PASSWORD_DEFAULT)
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password, gender, age, weight, height) 
            VALUES ('$username', '$email', '$password', '$gender', '$age', '$weight', '$height')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.html'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
