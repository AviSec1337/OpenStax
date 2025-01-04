<?php
require '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle course addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $courseName = trim($_POST['course_name']);
    $courseDescription = trim($_POST['course_description']);

    if (!empty($courseName) && !empty($courseDescription)) {
        $sql = "INSERT INTO courses (name, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $courseName, $courseDescription);

        if ($stmt->execute()) {
            $success = "Course added successfully.";
        } else {
            $error = "Error adding course: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

// Fetch all courses
$sql = "SELECT * FROM courses";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">Admin Dashboard</div>
            <nav class="mt-4">
                <a href="admin_dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
                <a href="admin_users.php" class="block py-2 px-4 hover:bg-gray-700">Manage Users</a>
                <!-- <a href="admin_courses.php" class="block py-2 px-4 bg-gray-700">Manage Courses</a> -->
                <a href="admin_resources.php" class="block py-2 px-4 hover:bg-gray-700">Manage Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 hover:bg-gray-700">Manage Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Manage Courses</h1>

            <!-- Add Course Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Add New Course</h2>
                <?php if (isset($success)): ?>
                    <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
                <?php elseif (isset($error)): ?>
                    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
                <?php endif; ?>
                <form action="admin_courses.php" method="POST">
                    <div class="mb-4">
                        <label for="course_name" class="block text-gray-700 font-bold mb-2">Course Name</label>
                        <input type="text" id="course_name" name="course_name" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="course_description" class="block text-gray-700 font-bold mb-2">Course Description</label>
                        <textarea id="course_description" name="course_description" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <button type="submit" name="add_course" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Add Course</button>
                </form>
            </div>

            <!-- Courses Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Existing Courses</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Course Name</th>
                            <th class="border border-gray-300 px-4 py-2">Description</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($course = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $course['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($course['name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($course['description']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="edit_course.php?id=<?php echo $course['id']; ?>" class="text-blue-500">Edit</a>
                                    <a href="delete_course.php?id=<?php echo $course['id']; ?>" class="text-red-500 ml-2">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
