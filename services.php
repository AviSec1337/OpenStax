<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - OpenStax</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white py-4 shadow-lg">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-3xl font-bold">OpenStax</h1>
            <nav>
                <a href="index.php" class="mx-4 hover:text-gray-200">Home</a>
                <a href="aboutus.php" class="mx-4 hover:text-gray-200">About Us</a>
                <a href="reviews_login.php" class="mx-4 hover:text-gray-200">Reviews</a>
                <a href="pages/user_login.php" class="mx-4 hover:text-gray-200">User Login</a>
                <a href="pages/admin_login.php" class="mx-4 hover:text-gray-200">Admin Login</a>
            </nav>
        </div>
    </header>

    <!-- Services Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-6">Our Services</h2>
            <p class="text-lg text-gray-700 text-center mb-8">
                At OpenStax, we provide a range of services to support both educators and students. 
                From resource management to collaborative tools, our services are designed to enhance the learning experience.
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Resource Management</h3>
                    <p class="text-gray-700">Upload, share, and access approved resources including notes, videos, audios, and YouTube links.</p>
                </div>
                <!-- Service 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Course Collaboration</h3>
                    <p class="text-gray-700">Collaborate with educators and students to create and manage courses efficiently.</p>
                </div>
                <!-- Service 3 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Permission-Based Access</h3>
                    <p class="text-gray-700">Ensure high-quality content through admin approval for shared resources.</p>
                </div>
                <!-- Service 4 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Secure Login System</h3>
                    <p class="text-gray-700">Protect user data with role-based access control for both users and admins.</p>
                </div>
                <!-- Service 5 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Personalized Dashboards</h3>
                    <p class="text-gray-700">Track your uploaded resources, approvals, and overall activity in a dedicated dashboard.</p>
                </div>
                <!-- Service 6 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Student and Tutor Engagement</h3>
                    <p class="text-gray-700">Connect with peers and educators for collaborative learning and shared success.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="bg-blue-100 py-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Experience the OpenStax Advantage</h2>
            <p class="text-lg text-gray-700 mb-6">
                Join thousands of educators and students who trust OpenStax for their learning needs. 
                Whether you're managing resources, creating courses, or sharing knowledge, OpenStax is here to support you.
            </p>
            <a href="pages/user_register.php" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600">Get Started</a>
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
