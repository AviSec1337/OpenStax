<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStax - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        footer { margin-top: auto; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white py-4 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="#" class="logo flex items-center space-x-2 text-white text-2xl">
                <ion-icon name="desktop-outline" class="text-3xl"></ion-icon>
                <span>OpenStax</span>
            </a>
            <nav>
                <a href="aboutus.php" class="mx-4 hover:text-gray-200">About Us</a>
                <a href="services.php" class="mx-4 hover:text-gray-200">Services</a>
                <a href="reviews_login.php" class="mx-4 hover:text-gray-200">Reviews</a>
                <a href="pages/user_login.php" class="mx-4 hover:text-gray-200">User Login</a>
                <a href="pages/admin_login.php" class="mx-4 hover:text-gray-200">Admin Login</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-blue-100 py-20 text-center">
        <div class="container mx-auto">
            <h2 class="text-4xl font-bold mb-4">Welcome to OpenStax</h2>
            <p class="text-lg text-gray-700 mb-6">
                OpenStax is your trusted platform for managing educational resources and collaborative learning. 
                Designed for educators, students, and institutions, OpenStax makes it easy to share, access, and organize 
                essential learning materials securely and efficiently.
            </p>
            <p class="text-lg text-gray-700 mb-6">
                Experience a seamless way to upload and share notes, videos, audio, and more with state-of-the-art tools 
                for resource approval, course creation, and personalized dashboards.
            </p>
            <a href="aboutus.php" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Learn More</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Why Choose OpenStax?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Comprehensive Resource Management</h3>
                    <p class="text-gray-700">Upload, share, and access approved educational materials seamlessly.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Secure User Authentication</h3>
                    <p class="text-gray-700">Protect your data with robust login and role-based access controls for users and admins.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Real-Time Resource Sharing</h3>
                    <p class="text-gray-700">Collaborate efficiently with students and teachers in a secure environment.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Permission-Based Access</h3>
                    <p class="text-gray-700">Admin approval ensures high-quality and verified content for all users.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Personalized Dashboards</h3>
                    <p class="text-gray-700">Track your activities, resources, and permissions with an intuitive dashboard.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Flexible Resource Types</h3>
                    <p class="text-gray-700">Share diverse content formats like notes, videos, audios, and YouTube links.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p class="text-sm">&copy; <?php echo date('Y'); ?> OpenStax. All rights reserved.</p>
            <p class="text-sm">Empowering Learning for Educators and Students Worldwide.</p>
        </div>
    </footer>
</body>
</html>
