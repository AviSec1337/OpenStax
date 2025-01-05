<?php
require 'db.php';
session_start();

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM register WHERE email = ? AND role = 'user'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: reviews.php');
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Reviews</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-image: url('assets/user_login.jpg'); /* Add your background image here */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .overlay {
            background-color: rgba(0, 0, 0, 0.6); /* Dark overlay for better text visibility */
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen relative">
    <div class="overlay"></div>
    <div class="bg-white p-8 rounded shadow-lg w-96 z-10">
        <h2 class="text-2xl font-bold text-center mb-6">Login to Review</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="reviews_login.php" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">Login</button>
        </form>
        <p class="text-center mt-4">Don't have an account? <a href="reviews_signup.php" class="text-blue-500">Sign up</a></p>
    </div>
</body>
</html>
