<?php
require '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Get the user ID from the URL
if (!isset($_GET['id'])) {
    header('Location: admin_dashboard.php');
    exit;
}

$userId = $_GET['id'];

// Delete the user from the database
$sqlDelete = "DELETE FROM register WHERE id = ?";
$stmtDelete = $conn->prepare($sqlDelete);
$stmtDelete->bind_param('i', $userId);

if ($stmtDelete->execute()) {
    $success = "User deleted successfully.";
} else {
    $error = "Error deleting user: " . $conn->error;
}

// Redirect back to the admin dashboard
header('Location: admin_dashboard.php');
exit;
?>
