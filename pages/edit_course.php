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

// Fetch course details
$sql = "SELECT * FROM courses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $courseId);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    header('Location: admin_courses.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = trim($_POST['course_name']);
    $courseDescription = trim($_POST['course_description']);

    if (!empty($courseName) && !empty($courseDescription)) {
        // Update course details
        $sqlUpdate = "UPDATE courses SET name = ?, description = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ssi', $courseName, $courseDescription, $courseId);

        if ($stmtUpdate->execute()) {
            $success = "Course updated successfully.";
            header('Location: admin_courses.php');
            exit;
        } else {
            $error = "Error updating course: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4 text-center">Edit Course</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="edit_course.php?id=<?php echo $courseId; ?>" method="POST">
            <div class="mb-4">
                <label for="course_name" class="block text-gray-700 font-bold mb-2">Course Name</label>
                <input type="text" id="course_name" name="course_name" value="<?php echo htmlspecialchars($course['name']); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="course_description" class="block text-gray-700 font-bold mb-2">Course Description</label>
                <textarea id="course_description" name="course_description" class="w-full p-2 border rounded" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Update Course</button>
        </form>
    </div>
</body>
</html>
