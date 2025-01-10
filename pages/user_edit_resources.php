<?php
require '../db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: user_login.php');
    exit;
}

// Get user ID and resource ID
$userId = $_SESSION['user_id'];
$resourceId = isset($_GET['id']) ? intval($_GET['id']) : null;

// Debugging - Check ID
if (!$resourceId) {
    die('Resource ID is missing or invalid.');
}

// Fetch resource details for editing
$sql = "SELECT * FROM resources WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $resourceId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$resource = $result->fetch_assoc();

if (!$resource) {
    die('Resource not found or you do not have permission to edit it.');
}

// Handle resource update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $type = $_POST['type'];
    $filePath = $resource['file_path']; // Preserve existing file path

    // Handle file upload or YouTube link update
    if ($type === 'youtube') {
        $filePath = trim($_POST['youtube_link']);
    } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {
        $uploadDir = '../uploads/';
        $filePath = $uploadDir . basename($_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
    }

    if (!empty($name) && !empty($description) && !empty($type) && !empty($filePath)) {
        $sqlUpdate = "UPDATE resources SET name = ?, description = ?, file_path = ?, type = ? WHERE id = ? AND user_id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ssssii', $name, $description, $filePath, $type, $resourceId, $userId);

        if ($stmtUpdate->execute()) {
            $success = "Resource updated successfully.";
            header('Location: user_resources.php');
            exit;
        } else {
            $error = "Error updating resource: " . $stmtUpdate->error;
        }
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resource</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6">Edit Resource</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="user_edit_resources.php?id=<?php echo $resourceId; ?>" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">Resource Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($resource['name']); ?>" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea id="description" name="description" class="w-full p-2 border rounded" required><?php echo htmlspecialchars($resource['description']); ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-gray-700 font-bold mb-2">Resource Type</label>
                    <select id="type" name="type" class="w-full p-2 border rounded" required>
                        <option value="file" <?php echo $resource['type'] === 'file' ? 'selected' : ''; ?>>File</option>
                        <option value="audio" <?php echo $resource['type'] === 'audio' ? 'selected' : ''; ?>>Audio</option>
                        <option value="video" <?php echo $resource['type'] === 'video' ? 'selected' : ''; ?>>Video</option>
                        <option value="youtube" <?php echo $resource['type'] === 'youtube' ? 'selected' : ''; ?>>YouTube Link</option>
                    </select>
                </div>
                <div class="mb-4" id="file-upload" <?php echo $resource['type'] === 'youtube' ? 'style="display:none;"' : ''; ?>>
                    <label for="file" class="block text-gray-700 font-bold mb-2">Upload File</label>
                    <input type="file" id="file" name="file" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4" id="youtube-link" <?php echo $resource['type'] !== 'youtube' ? 'style="display:none;"' : ''; ?>>
                    <label for="youtube_link" class="block text-gray-700 font-bold mb-2">YouTube Link</label>
                    <input type="url" id="youtube_link" name="youtube_link" value="<?php echo $resource['type'] === 'youtube' ? htmlspecialchars($resource['file_path']) : ''; ?>" class="w-full p-2 border rounded">
                </div>
                <button type="submit" name="update" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Resource</button>
            </form>

            <!-- View Resource Button -->
            <div class="mt-4">
                <h2 class="text-lg font-bold">View Resource</h2>
                <a href="<?php echo htmlspecialchars($resource['file_path']); ?>" target="_blank" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-block mt-2">View Resource</a>
            </div>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('type');
        const fileUpload = document.getElementById('file-upload');
        const youtubeLink = document.getElementById('youtube-link');

        typeSelect.addEventListener('change', function () {
            if (this.value === 'youtube') {
                youtubeLink.style.display = 'block';
                fileUpload.style.display = 'none';
            } else {
                youtubeLink.style.display = 'none';
                fileUpload.style.display = 'block';
            }
        });
    </script>
</body>
</html>
