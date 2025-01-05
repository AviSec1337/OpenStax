<?php
require 'db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: reviews_login.php');
    exit;
}

// Handle new review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $fullName = trim($_POST['full_name']);
    $comment = trim($_POST['comment']);

    if (!empty($fullName) && !empty($comment)) {
        $sql = "INSERT INTO review (full_name, comment) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $fullName, $comment);
        if ($stmt->execute()) {
            $success = "Review added successfully!";
        } else {
            $error = "Failed to add review.";
        }
    } else {
        $error = "All fields are required.";
    }
}

// Fetch all reviews
$sqlReviews = "SELECT * FROM review ORDER BY id DESC";
$reviews = $conn->query($sqlReviews);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews - OpenStax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white py-4 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="index.php" class="logo flex items-center space-x-2 text-white text-2xl">
                <ion-icon name="desktop-outline" class="text-3xl"></ion-icon>
                <span>OpenStax</span>
            </a>
            <nav>
                <a href="index.php" class="mx-4 hover:text-gray-200">Home</a>
                <a href="aboutus.php" class="mx-4 hover:text-gray-200">About Us</a>
                <a href="services.php" class="mx-4 hover:text-gray-200">Services</a>
                <a href="index.php" class="mx-4 hover:text-gray-200">Logout</a>
            </nav>
        </div>
    </header>

    <!-- Review Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-6">User Reviews</h2>

            <!-- Success/Error Message -->
            <?php if (isset($success)): ?>
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4"><?php echo $success; ?></div>
            <?php elseif (isset($error)): ?>
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Review Form -->
            <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
                <form action="reviews.php" method="POST">
                    <div class="mb-4">
                        <label for="full_name" class="block text-gray-700 font-bold mb-2">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="comment" class="block text-gray-700 font-bold mb-2">Your Review</label>
                        <textarea id="comment" name="comment" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Review</button>
                </form>
            </div>

            <!-- Display Reviews -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-2xl font-bold mb-4">What Others Say</h3>
                <?php while ($review = $reviews->fetch_assoc()): ?>
                    <div class="mb-4">
                        <h4 class="text-lg font-bold"><?php echo htmlspecialchars($review['full_name']); ?></h4>
                        <p class="text-gray-700"><?php echo htmlspecialchars($review['comment']); ?></p>
                        <hr class="my-2">
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p class="text-sm">&copy; <?php echo date('Y'); ?> OpenStax. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
