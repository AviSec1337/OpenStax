<?php
require 'db.php';

$result = $conn->query("SELECT * FROM register WHERE email = 'test@example.com'");
if ($result->num_rows > 0) {
    echo "Database connection and query are working!";
} else {
    echo "No user found or database issue!";
}
?>
