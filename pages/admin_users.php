<?php
require '../db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $deleteId = intval($_GET['delete_id']);

    // Check if the user exists
    $sqlCheck = "SELECT * FROM register WHERE id = ? AND role = 'user'";
    $stmt = $conn->prepare($sqlCheck);
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Delete the user
        $sqlDelete = "DELETE FROM register WHERE id = ?";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bind_param('i', $deleteId);

        if ($stmtDelete->execute()) {
            $success = "User deleted successfully.";
        } else {
            $error = "Failed to delete user.";
        }
    } else {
        $error = "User not found.";
    }

    // Redirect back to avoid form resubmission
    header('Location: admin_users.php');
    exit;
}

// Fetch all users
$sqlUsers = "SELECT * FROM register WHERE role = 'user'";
$userList = $conn->query($sqlUsers);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white h-screen">
            <div class="p-4 text-xl font-bold text-center">Admin Dashboard</div>
            <nav class="mt-4">
                <a href="admin_dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a>
                <a href="admin_users.php" class="block py-2 px-4 bg-gray-700">Manage Users</a>
                <a href="admin_resources.php" class="block py-2 px-4 hover:bg-gray-700">Manage Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 hover:bg-gray-700">Manage Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Manage Users</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- User List -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Registered Users</h2>
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">ID</th>
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Email</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $userList->fetch_assoc()): ?>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2"><?php echo $user['id']; ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <!-- Edit Button -->
                                    <a href="edit_user.php?user_id=<?php echo $user['id']; ?>" 
                                       class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                       Edit
                                    </a>
                                    <!-- Delete Button -->
                                    <a href="admin_users.php?delete_id=<?php echo $user['id']; ?>" 
                                       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 ml-2">
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
</body>
</html>
