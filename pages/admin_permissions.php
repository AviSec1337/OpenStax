<?php
require '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle approval or denial of permissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $permissionId = $_POST['permission_id'];
    $action = $_POST['action'];

    $sql = $action === 'approve'
        ? "UPDATE permissions SET status = 'approved' WHERE id = ?"
        : "UPDATE permissions SET status = 'denied' WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $permissionId);
    $stmt->execute();
}

// Fetch all permissions
$sql = "SELECT p.id, r.first_name, r.last_name, p.resource_id, p.resource_type, p.status, p.requested_at 
        FROM permissions p 
        JOIN register r ON p.user_id = r.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Permissions</title>
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
                <!-- <a href="admin_courses.php" class="block py-2 px-4 hover:bg-gray-700">Manage Courses</a> -->
                <a href="admin_resources.php" class="block py-2 px-4 hover:bg-gray-700">Manage Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 bg-gray-700">Manage Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Manage Permissions</h1>

            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">User</th>
                        <th class="border border-gray-300 px-4 py-2">Resource ID</th>
                        <th class="border border-gray-300 px-4 py-2">Type</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                        <th class="border border-gray-300 px-4 py-2">Requested At</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($permission = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($permission['first_name'] . ' ' . $permission['last_name']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $permission['resource_id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($permission['resource_type']); ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php if ($permission['status'] === 'pending'): ?>
                                    <span class="text-yellow-500">Pending</span>
                                <?php elseif ($permission['status'] === 'approved'): ?>
                                    <span class="text-green-500">Approved</span>
                                <?php elseif ($permission['status'] === 'denied'): ?>
                                    <span class="text-red-500">Denied</span>
                                <?php endif; ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $permission['requested_at']; ?></td>
                            <td class="border border-gray-300 px-4 py-2">
                                <?php if ($permission['status'] === 'pending'): ?>
                                    <form action="admin_permissions.php" method="POST" class="inline-block">
                                        <input type="hidden" name="permission_id" value="<?php echo $permission['id']; ?>">
                                        <button name="action" value="approve" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="admin_permissions.php" method="POST" class="inline-block">
                                        <input type="hidden" name="permission_id" value="<?php echo $permission['id']; ?>">
                                        <button name="action" value="deny" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">
                                            Deny
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-gray-500">No actions available</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
