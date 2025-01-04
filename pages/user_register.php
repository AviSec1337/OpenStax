<?php
require '../db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validation
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Check if the email already exists
        $sql = "SELECT * FROM register WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already exists.";
        } else {
            // Insert new user into the database
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO register (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $firstName, $lastName, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-lg w-96">
        <h1 class="text-2xl font-bold mb-4 text-center">Register</h1>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="user_register.php" method="POST">
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700 font-bold mb-2">First Name</label>
                <input type="text" id="first_name" name="first_name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-gray-700 font-bold mb-2">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Register</button>
        </form>
        <p class="text-center mt-4">Already have an account? <a href="user_login.php" class="text-blue-500">Login</a></p>
    </div>
</body>
</html>
