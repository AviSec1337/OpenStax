<?php
require 'db.php';
session_start();

// Handle signup
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

    if (!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password)) {
        $sql = "INSERT INTO register (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, 'user')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $firstName, $lastName, $email, $password);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            header('Location: reviews.php');
            exit;
        } else {
            $error = "Failed to register.";
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
    <title>Sign Up - Reviews</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold text-center mb-6">Sign Up to Review</h2>
            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="reviews_signup.php" method="POST">
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
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">Sign Up</button>
            </form>
            <p class="text-center mt-4">Already have an account? <a href="reviews_login.php" class="text-blue-500">Log in</a></p>
        </div>
    </div>
</body>
</html>
