<?php
require '../db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle resource deletion
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);

    // Check if the resource exists
    $sqlCheck = "SELECT * FROM resources WHERE id = ?";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the resource
        $sqlDelete = "DELETE FROM resources WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('i', $deleteId);
        if ($stmtDelete->execute()) {
            $success = "Resource deleted successfully.";
        } else {
            $error = "Failed to delete resource.";
        }
    } else {
        $error = "Resource not found.";
    }
}

// Handle resource addition
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $type = $_POST['type'];
    $filePath = '';

    if ($type === 'youtube') {
        $filePath = trim($_POST['youtube_link']);
    } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $uploadDir = '../uploads/';
        $filePath = $uploadDir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
    }

    if (!empty($name) && !empty($description) && !empty($type) && !empty($filePath)) {
        $sql = "INSERT INTO resources (name, description, file_path, type, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $name, $description, $filePath, $type);

        if ($stmt->execute()) {
            $success = "Resource added successfully.";
        } else {
            $error = "Error adding resource.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all resources
$sql = "SELECT * FROM resources";
$result = $conn->query($sql);

// Function to extract YouTube video ID
function extractYouTubeID($url) {
    parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
    return $queryParams['v'] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Resources</title>
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
                <a href="admin_resources.php" class="block py-2 px-4 bg-gray-700">Manage Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 hover:bg-gray-700">Manage Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Manage Resources</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Add Resource Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Add Resource</h2>
                <form action="admin_resources.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 font-bold mb-2">Resource Name</label>
                        <input type="text" id="name" name="name" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                        <textarea id="description" name="description" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="type" class="block text-gray-700 font-bold mb-2">Resource Type</label>
                        <select id="type" name="type" class="w-full p-2 border rounded" required>
                            <option value="file">File</option>
                            <option value="audio">Audio</option>
                            <option value="video">Video</option>
                            <option value="youtube">YouTube Link</option>
                        </select>
                    </div>
                    <div class="mb-4" id="file-upload">
                        <label for="file" class="block text-gray-700 font-bold mb-2">Upload File</label>
                        <input type="file" id="file" name="file" class="w-full p-2 border rounded">
                    </div>
                    <div class="mb-4 hidden" id="youtube-link">
                        <label for="youtube_link" class="block text-gray-700 font-bold mb-2">YouTube Link</label>
                        <input type="url" id="youtube_link" name="youtube_link" class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Resource</button>
                </form>
            </div>

            <!-- Resource Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">All Resources</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Type</th>
                            <th class="border border-gray-300 px-4 py-2">Preview</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['type']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <?php if ($row['type'] === 'youtube'): ?>
                                        <iframe width="200" height="150" src="https://www.youtube.com/embed/<?php echo extractYouTubeID($row['file_path']); ?>" frameborder="0" allowfullscreen></iframe>
                                    <?php elseif ($row['type'] === 'video' || $row['type'] === 'audio'): ?>
                                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="text-blue-500">View</a>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download class="text-blue-500">Download</a>
                                    <?php endif; ?>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <a href="admin_resources.php?delete_id=<?php echo $row['id']; ?>" 
                                       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('type');
        const fileUpload = document.getElementById('file-upload');
        const youtubeLink = document.getElementById('youtube-link');

        typeSelect.addEventListener('change', function() {
            if (this.value === 'youtube') {
                youtubeLink.classList.remove('hidden');
                fileUpload.classList.add('hidden');
            } else {
                youtubeLink.classList.add('hidden');
                fileUpload.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
