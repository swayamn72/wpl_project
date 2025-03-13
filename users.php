<?php
include 'db_connect.php';

$sql = "SELECT id, username, email, weight, height FROM users ORDER BY id DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Registered Users</h2>";
    echo "<table border='1'><tr><th>ID</th><th>Username</th><th>Email</th><th>Weight</th><th>Height</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row['id']."</td>
                <td>".$row['username']."</td>
                <td>".$row['email']."</td>
                <td>".$row['weight']." kg</td>
                <td>".$row['height']." cm</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "No users found.";
}

$conn->close();
?>
