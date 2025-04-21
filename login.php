<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Fetch user details
    $sql = "SELECT user_id, username, height, weight, password, gender, age FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($password == $row['password']) {  // Use password_verify() if using hashing
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['gender'] = $row['gender'];
            $_SESSION['age'] = $row['age'];
            $_SESSION['height'] = $row['height'];
            $_SESSION['weight'] = $row['weight'];

            // Check if the login entry already exists for today
            $user_id = $_SESSION['user_id'];
            $today = date("Y-m-d");

            $check_sql = "SELECT * FROM login_logs WHERE user_id = ? AND login_date = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("is", $user_id, $today);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                // Insert login log if it doesn't exist
                $insert_sql = "INSERT INTO login_logs (user_id, login_date) VALUES (?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("is", $user_id, $today);
                $insert_stmt->execute();
                $insert_stmt->close();
            }

            $check_stmt->close();

            // Redirect to the home page
            header("Location: home.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }

    $stmt->close();
}

$conn->close();
?>
