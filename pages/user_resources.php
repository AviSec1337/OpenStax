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

// Handle resource upload
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
            $resourceId = $conn->insert_id;

            // Add a pending permission request
            $sqlPermission = "INSERT INTO permissions (user_id, resource_id, resource_type, status) VALUES (?, ?, ?, 'pending')";
            $stmtPermission = $conn->prepare($sqlPermission);
            $stmtPermission->bind_param('iis', $userId, $resourceId, $type);
            $stmtPermission->execute();

            $success = "Resource uploaded successfully and sent for admin approval.";
        } else {
            $error = "Error uploading resource.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all approved resources
$sql = "SELECT * FROM resources WHERE id IN (SELECT resource_id FROM permissions WHERE status = 'approved')";
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
    <title>User Resources</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">User Dashboard</div>
            <nav class="mt-4">
                <a href="user_dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
                <a href="user_resources.php" class="block py-2 px-4 bg-gray-700">Resources</a>
                <!-- <a href="user_courses.php" class="block py-2 px-4 bg-gray-700">Courses</a> -->
                <a href="user_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Resources</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Add Resource Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Upload Resource</h2>
                <form action="user_resources.php" method="POST" enctype="multipart/form-data">
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
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Upload</button>
                </form>
            </div>

            <!-- All Approved Resources -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Shared Resources</h2>
                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Files</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $result->data_seek(0); // Reset result set pointer
                        while ($row = $result->fetch_assoc()): 
                            if ($row['type'] === 'file'): ?>
                                <div class="p-4 bg-gray-100 rounded-lg shadow">
                                    <h4 class="font-bold"><?php echo htmlspecialchars($row['name']); ?></h4>
                                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download class="text-blue-500 mt-2 block">Download</a>
                                </div>
                        <?php endif; endwhile; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Audio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()): 
                            if ($row['type'] === 'audio'): ?>
                                <div class="p-4 bg-gray-100 rounded-lg shadow">
                                    <h4 class="font-bold"><?php echo htmlspecialchars($row['name']); ?></h4>
                                    <audio controls>
                                        <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                </div>
                        <?php endif; endwhile; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">Videos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()): 
                            if ($row['type'] === 'video'): ?>
                                <div class="p-4 bg-gray-100 rounded-lg shadow">
                                    <h4 class="font-bold"><?php echo htmlspecialchars($row['name']); ?></h4>
                                    <video controls class="w-full mt-2">
                                        <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                        <?php endif; endwhile; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-lg font-bold mb-2">YouTube Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()): 
                            if ($row['type'] === 'youtube'): ?>
                                <div class="p-4 bg-gray-100 rounded-lg shadow">
                                    <h4 class="font-bold"><?php echo htmlspecialchars($row['name']); ?></h4>
                                    <iframe class="w-full mt-2" height="315" src="https://www.youtube.com/embed/<?php echo extractYouTubeID($row['file_path']); ?>" frameborder="0" allowfullscreen></iframe>
                                </div>
                        <?php endif; endwhile; ?>
                    </div>
                </div>
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
