<?php
require '../db.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch the user ID to edit
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: admin_users.php');
    exit;
}
$userId = intval($_GET['user_id']);

// Fetch user details
$sql = "SELECT * FROM register WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: admin_users.php');
    exit;
}
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($firstName) || empty($lastName) || empty($email) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Update user details in the database
        $sqlUpdate = "UPDATE register SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param('ssssi', $firstName, $lastName, $email, $role, $userId);

        if ($stmtUpdate->execute()) {
            $success = "User details updated successfully.";
            // Refresh user data
            $user['first_name'] = $firstName;
            $user['last_name'] = $lastName;
            $user['email'] = $email;
            $user['role'] = $role;
        } else {
            $error = "Failed to update user details.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
                <a href="admin_resources.php" class="block py-2 px-4 hover:bg-gray-700">Resources</a>
                <a href="admin_permissions.php" class="block py-2 px-4 hover:bg-gray-700">Permissions</a>
                <a href="admin_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a>
                <a href="admin_logout.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Edit User</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Edit User Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form action="edit_user.php?user_id=<?php echo $userId; ?>" method="POST">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-gray-700 font-bold mb-2">Role</label>
                        <select id="role" name="role" class="w-full p-2 border rounded" required>
                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
