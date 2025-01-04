<?php
require '../db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

// Fetch admin details
$adminId = $_SESSION['admin_id'];
$sql = "SELECT * FROM register WHERE id = ? AND role = 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);

    if (!empty($firstName) && !empty($lastName) && !empty($email)) {
        $sql = "UPDATE register SET first_name = ?, last_name = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', $firstName, $lastName, $email, $adminId);

        if ($stmt->execute()) {
            $success = "Profile updated successfully.";
            $_SESSION['name'] = $firstName . ' ' . $lastName;
        } else {
            $error = "Error updating profile.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        if (password_verify($currentPassword, $admin['password'])) {
            if ($newPassword === $confirmPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $sql = "UPDATE register SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $hashedPassword, $adminId);

                if ($stmt->execute()) {
                    $success = "Password updated successfully.";
                } else {
                    $error = "Error updating password.";
                }
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
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
    <title>Admin Profile</title>
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
                <a href="admin_profile.php" class="block py-2 px-4 bg-gray-700">Profile</a>
                <a href="../index.php" class="block py-2 px-4 text-red-500 hover:bg-gray-700">Logout</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-2xl font-bold mb-6">Admin Profile</h1>

            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Profile Update Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
                <h2 class="text-xl font-bold mb-4">Update Profile</h2>
                <form action="admin_profile.php" method="POST">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($admin['first_name']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($admin['last_name']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="w-full p-2 border rounded" required>
                    </div>
                    <button type="submit" name="update_profile" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Profile</button>
                </form>
            </div>

            <!-- Password Update Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h2 class="text-xl font-bold mb-4">Change Password</h2>
                <form action="admin_profile.php" method="POST">
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 font-bold mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 font-bold mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-gray-700 font-bold mb-2">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded" required>
                    </div>
                    <button type="submit" name="update_password" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
