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
$sql = "SELECT * FROM register WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch global approved resources
$sqlStats = "SELECT type, COUNT(*) as count FROM resources WHERE id IN (SELECT resource_id FROM permissions WHERE status = 'approved') GROUP BY type";
$stmt = $conn->prepare($sqlStats);
$stmt->execute();
$statsResult = $stmt->get_result();

$resourceStats = [];
while ($row = $statsResult->fetch_assoc()) {
    $resourceStats[$row['type']] = $row['count'];
}

// Fetch user's resource statuses
$sqlStatus = "SELECT status, COUNT(*) as count FROM permissions WHERE user_id = ? GROUP BY status";
$stmt = $conn->prepare($sqlStatus);
$stmt->bind_param('i', $userId);
$stmt->execute();
$statusResult = $stmt->get_result();

$statusStats = [];
while ($row = $statusResult->fetch_assoc()) {
    $statusStats[$row['status']] = $row['count'];
}

// Fetch individual resource statuses for the user
$sqlUserResources = "SELECT r.type, r.name, p.status FROM resources r 
    JOIN permissions p ON r.id = p.resource_id 
    WHERE p.user_id = ?";
$stmt = $conn->prepare($sqlUserResources);
$stmt->bind_param('i', $userId);
$stmt->execute();
$userResourcesResult = $stmt->get_result();

$userResources = [];
while ($row = $userResourcesResult->fetch_assoc()) {
    $userResources[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">User Dashboard</div>
            <nav class="mt-4">
                <a href="user_dashboard.php" class="block py-2 px-4 bg-gray-700">Dashboard</a>
                <a href="user_resources.php" class="block py-2 px-4 hover:bg-gray-700">Resources</a>
                <a href="user_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h1>

            <!-- Global Resource Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold">Files Shared</h2>
                    <p class="text-3xl mt-2"><?php echo $resourceStats['file'] ?? 0; ?></p>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold">Audios Shared</h2>
                    <p class="text-3xl mt-2"><?php echo $resourceStats['audio'] ?? 0; ?></p>
                </div>
                <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold">Videos Shared</h2>
                    <p class="text-3xl mt-2"><?php echo $resourceStats['video'] ?? 0; ?></p>
                </div>
                <div class="bg-red-500 text-white p-4 rounded-lg shadow-lg">
                    <h2 class="text-lg font-bold">YouTube Links</h2>
                    <p class="text-3xl mt-2"><?php echo $resourceStats['youtube'] ?? 0; ?></p>
                </div>
            </div>

            <!-- User Resource Table -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Your Uploaded Resources</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2">Resource Type</th>
                            <th class="border px-4 py-2">Resource Name</th>
                            <th class="border px-4 py-2">Approval Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($userResources as $resource): ?>
                            <tr>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($resource['type']); ?></td>
                                <td class="border px-4 py-2"><?php echo htmlspecialchars($resource['name']); ?></td>
                                <td class="border px-4 py-2">
                                    <?php 
                                    // Conditional styling for statuses
                                    $statusClass = '';
                                    if ($resource['status'] === 'approved') {
                                        $statusClass = 'text-green-500 font-bold';
                                    } elseif ($resource['status'] === 'pending') {
                                        $statusClass = 'text-yellow-500 font-bold';
                                    } elseif ($resource['status'] === 'denied') {
                                        $statusClass = 'text-red-500 font-bold';
                                    }
                                    ?>
                                    <span class="<?php echo $statusClass; ?>">
                                        <?php echo ucfirst(htmlspecialchars($resource['status'])); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resource Status Chart -->
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Your Resource Status</h2>
                <div class="w-64 h-64 mx-auto">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // User Resource Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Approved', 'Denied'],
                datasets: [{
                    label: 'Your Resources',
                    data: [
                        <?php echo $statusStats['pending'] ?? 0; ?>,
                        <?php echo $statusStats['approved'] ?? 0; ?>,
                        <?php echo $statusStats['denied'] ?? 0; ?>
                    ],
                    backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
