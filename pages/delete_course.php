<?php
require '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Get the course ID from the URL
if (!isset($_GET['id'])) {
    header('Location: admin_courses.php');
    exit;
}

$courseId = $_GET['id'];

// Delete the course from the database
$sql = "DELETE FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $courseId);

if ($stmt->execute()) {
    $success = "Course deleted successfully.";
} else {
    $error = "Error deleting course: " . $conn->error;
}

// Redirect back to the admin courses page with success or error message
header('Location: admin_courses.php');
exit;
?>
