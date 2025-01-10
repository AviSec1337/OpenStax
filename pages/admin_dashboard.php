<?php
require '../db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch admin details
$adminId = $_SESSION['admin_id'];
$sql = "SELECT * FROM register WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Fetch statistics
// Total Users
$sqlUsers = "SELECT COUNT(*) as total_users FROM register WHERE role = 'user'";
$resultUsers = $conn->query($sqlUsers);
$totalUsers = $resultUsers->fetch_assoc()['total_users'] ?? 0;

// Total Resources
$sqlResources = "SELECT COUNT(*) as total_resources FROM resources";
$resultResources = $conn->query($sqlResources);
$totalResources = $resultResources->fetch_assoc()['total_resources'] ?? 0;

// Pending Permissions
$sqlPending = "SELECT COUNT(*) as pending_permissions FROM permissions WHERE status = 'pending'";
$resultPending = $conn->query($sqlPending);
$pendingPermissions = $resultPending->fetch_assoc()['pending_permissions'] ?? 0;

// Fetch registered users
$sqlUserList = "SELECT * FROM register WHERE role = 'user'";
$userList = $conn->query($sqlUserList);

// Fetch resources
$sqlResourceList = "SELECT * FROM resources";
$resourceList = $conn->query($sqlResourceList);

// Fetch permissions
$sqlPermissionList = "SELECT permissions.id, permissions.resource_id, permissions.resource_type, permissions.status, register.first_name, register.last_name 
                      FROM permissions 
                      JOIN register ON permissions.user_id = register.id";
$permissionList = $conn->query($sqlPermissionList);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">Admin Dashboard</div>
            <nav class="mt-4">
                <a href="admin_dashboard.php" class="block py-2 px-4 bg-gray-700">Dashboard</a>
                <a href="admin_users.php" class="block py-2 px-4 hover:bg-gray-700">Manage Users</a>
                <a href="admin_resources.php" class="block py-2 px-4 hover:bg-gray-700">Manage Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 hover:bg-gray-700">Manage Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($admin['first_name']); ?></h1>

            <!-- Dashboard Widgets -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-500 text-white p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold">Total Users</h2>
                    <p class="text-3xl mt-2"><?php echo $totalUsers; ?></p>
                </div>
                <div class="bg-green-500 text-white p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold">Total Resources</h2>
                    <p class="text-3xl mt-2"><?php echo $totalResources; ?></p>
                </div>
                <div class="bg-yellow-500 text-white p-4 rounded-lg shadow-md">
                    <h2 class="text-lg font-bold">Pending Permissions</h2>
                    <p class="text-3xl mt-2"><?php echo $pendingPermissions; ?></p>
                </div>
            </div>

            <!-- Registered Users -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Registered Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Email</th>
                            <th class="border border-gray-300 px-4 py-2">Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $userList->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $user['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['role']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Resources -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Resources</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Type</th>
                            <th class="border border-gray-300 px-4 py-2">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($resource = $resourceList->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $resource['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($resource['name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($resource['type']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($resource['description']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Permissions -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Permissions</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Permission ID</th>
                            <th class="border border-gray-300 px-4 py-2">Resource ID</th>
                            <th class="border border-gray-300 px-4 py-2">Resource Type</th>
                            <th class="border border-gray-300 px-4 py-2">User Name</th>
                            <th class="border border-gray-300 px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($permission = $permissionList->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $permission['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $permission['resource_id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($permission['resource_type']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($permission['first_name'] . ' ' . $permission['last_name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <span class="<?php echo $permission['status'] === 'approved' ? 'text-green-500' : ($permission['status'] === 'denied' ? 'text-red-500' : 'text-yellow-500'); ?>">
                                        <?php echo ucfirst(htmlspecialchars($permission['status'])); ?>
                                    </span>
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
