<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - OpenStax</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
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
                <a href="services.php" class="mx-4 hover:text-gray-200">Services</a>
                <a href="reviews_login.php" class="mx-4 hover:text-gray-200">Reviews</a>
                <a href="pages/user_login.php" class="mx-4 hover:text-gray-200">User Login</a>
                <a href="pages/admin_login.php" class="mx-4 hover:text-gray-200">Admin Login</a>
            </nav>
        </div>
    </header>

    <!-- About Us Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-4xl font-bold text-center mb-6">About Us</h2>
            <p class="text-lg text-gray-700 text-center mb-6">
                OpenStax is a state-of-the-art Learning Management System designed to empower educators and students. 
                Our platform provides a collaborative environment for sharing resources, accessing approved materials, 
                and enhancing learning experiences for everyone.
            </p>
            <p class="text-lg text-gray-700 text-center">
                With OpenStax, institutions, teachers, and students can manage educational content with ease, ensuring 
                a secure, efficient, and user-friendly experience. Join us in revolutionizing education.
            </p>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 bg-blue-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Why Choose Us?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Reliable Content</h3>
                    <p class="text-gray-700">Access verified and approved resources for a trustworthy learning experience.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Collaborative Platform</h3>
                    <p class="text-gray-700">Engage with educators and peers in a secure and interactive environment.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <h3 class="text-xl font-bold mb-4">Enhanced Efficiency</h3>
                    <p class="text-gray-700">Streamline the sharing and organization of educational content.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tutors and Students Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-8">Our Community</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Tutor 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="assets/tutor1.jpg" alt="Tutor 1" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold mb-2">Abishek Shrestha</h3>
                    <p class="text-gray-700">CyberSecurity Expert</p>
                </div>
                <!-- Tutor 2 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="assets/tutor2.jpg" alt="Tutor 2" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold mb-2">John Hammond</h3>
                    <p class="text-gray-700">Web Application Developer</p>
                </div>
                <!-- Student 1 -->
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <img src="assets/student1.jpg" alt="Student 1" class="w-32 h-32 rounded-full mx-auto mb-4">
                    <h3 class="text-xl font-bold mb-2">Ryan M. Montgomery</h3>
                    <p class="text-gray-700">Web Developer Student</p>
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
