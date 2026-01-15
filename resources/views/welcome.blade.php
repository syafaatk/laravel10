<!-- buatkan halaman landing untuk aplikasi ini -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Application</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-blue-600">Our Application</h1>
            <nav>
                <a href="#features" class="text-gray-600 hover:text-blue-600 mx-4">Features</a>
                <a href="#about" class="text-gray-600 hover:text-blue-600 mx-4">About</a>
                <a href="#contact" class="text-gray-600 hover:text-blue-600 mx-4">Contact</a>
                <!-- login button if not logged in logout button if logged in -->
                @if (Auth::check())
                    <a href="{{ route('logout') }}" class="bg-red-600 text-white px-4 py-2 rounded-full hover:bg-red-700 transition">Logout</a>
                @else
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition">Login</a>
                @endif
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4 py-12">
        <section class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Welcome to Our Application</h2>
            <p class="text-lg text-gray-600 mb-6">Discover the features and benefits of using our app.</p>
            <a href="#features" class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition">Get Started</a>
        </section>

        <section id="features" class="mb-12">
            <h3 class="text-2xl font-bold mb-6 text-center">Features</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-bolt text-blue-600 text-4xl mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Fast Performance</h4>
                    <p class="text-gray-600">Experience lightning-fast load times and seamless interactions.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-shield-alt text-blue-600 text-4xl mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Secure</h4>
                    <p class="text-gray-600">Your data is protected with top-notch security measures.</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                    <i class="fas fa-cogs text-blue-600 text-4xl mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Customizable</h4>
                    <p class="text-gray-600">Tailor the application to fit your specific needs.</p>
                </div>
            </div>
        </section>

        <section id="about" class="mb-12">
            <h3 class="text-2xl font-bold mb-6 text-center">About Us</h3>
            <p class="text-center text-gray-600 max-w-2xl mx-auto">We are dedicated to providing the best application experience for our users. Our team is passionate about innovation and excellence.</p>
        </section>
        <section id="contact" class="mb-12">
            <h3 class="text-2xl font-bold mb-6 text-center">Contact Us</h3>
            <form class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="name">Name</label>
                    <input class="w-full px-3 py-2 border rounded-lg" type="text" id="name" placeholder="Your Name">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="email">Email</label>
                    <input class="w-full px-3 py-2 border rounded-lg" type="email" id="email" placeholder="Your Email">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="message">Message</label>
                    <textarea class="w-full px-3 py-2 border rounded-lg" id="message" placeholder="Your Message"></textarea>
                </div>  
                <button class="bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition" type="submit">Send Message</button>
            </form>
        </section>
    </main>
    <footer class="bg-white shadow mt-12">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            &copy; 2025 Our Application. All rights reserved.
        </div>
    </footer>
</body>
</html>