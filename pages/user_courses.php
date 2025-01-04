<?php
require '../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Fetch user details
$userId = $_SESSION['user_id'];

// Handle course upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseName = trim($_POST['course_name']);
    $courseDescription = trim($_POST['course_description']);
    $courseFilePath = '';

    // Handle file upload
    if (isset($_FILES['course_file']) && $_FILES['course_file']['error'] === 0) {
        $uploadDir = '../uploads/courses/';
        $courseFilePath = $uploadDir . basename($_FILES['course_file']['name']);
        move_uploaded_file($_FILES['course_file']['tmp_name'], $courseFilePath);
    }

    if (!empty($courseName) && !empty($courseDescription) && !empty($courseFilePath)) {
        $sql = "INSERT INTO courses (name, description, file_path, user_id, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $courseName, $courseDescription, $courseFilePath, $userId);

        if ($stmt->execute()) {
            $success = "Course uploaded successfully.";
        } else {
            $error = "Error uploading course.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all shared courses
$sql = "SELECT * FROM courses WHERE user_id = ? OR id IN (SELECT resource_id FROM permissions WHERE resource_type = 'course' AND status = 'approved')";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$courses = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">User Dashboard</div>
            <nav class="mt-4">
                <a href="user_dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
                <a href="user_resources.php" class="block py-2 px-4 hover:bg-gray-700">Resources</a>
                <!-- <a href="user_courses.php" class="block py-2 px-4 bg-gray-700">Courses</a> -->
                <a href="user_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="user_login.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Courses</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Add Course Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Upload Course</h2>
                <form action="user_courses.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="course_name" class="block text-gray-700 font-bold mb-2">Course Name</label>
                        <input type="text" id="course_name" name="course_name" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="course_description" class="block text-gray-700 font-bold mb-2">Course Description</label>
                        <textarea id="course_description" name="course_description" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="course_file" class="block text-gray-700 font-bold mb-2">Upload Course File</label>
                        <input type="file" id="course_file" name="course_file" class="w-full p-2 border rounded" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload Course</button>
                </form>
            </div>

            <!-- Shared Courses -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Shared Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <div class="p-4 bg-gray-100 rounded-lg shadow">
                            <h3 class="text-lg font-bold"><?php echo htmlspecialchars($course['name']); ?></h3>
                            <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars($course['description']); ?></p>
                            <a href="<?php echo htmlspecialchars($course['file_path']); ?>" download class="text-blue-500">Download Course</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
