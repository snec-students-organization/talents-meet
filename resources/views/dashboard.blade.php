<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talents Meet 2025</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

    <!-- 🌟 Navbar -->
    <nav class="bg-white shadow-md fixed w-full z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8">
                <h1 class="font-bold text-lg text-blue-700">Talents Meet 2025</h1>
            </div>

            <div class="space-x-6 text-sm font-medium">
                <a href="#about" class="text-gray-700 hover:text-blue-600">About</a>
                <a href="#events" class="text-gray-700 hover:text-blue-600">Events</a>
                <a href="#contact" class="text-gray-700 hover:text-blue-600">Contact</a>
                <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Login</a>
            </div>
        </div>
    </nav>

    <!-- 🌟 Hero Section -->
    <section class="pt-24 pb-16 bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-center">
        <div class="max-w-3xl mx-auto px-6">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4">Welcome to Talents Meet 2025 🎭</h2>
            <p class="text-lg mb-8 text-blue-100">
                The ultimate inter-collegiate fest celebrating creativity, innovation, and excellence across every talent!
            </p>
            <a href="{{ route('login') }}" class="bg-white text-blue-700 font-semibold px-6 py-3 rounded-md hover:bg-gray-100">
                Get Started
            </a>
        </div>
    </section>

    <!-- 🌟 About Section -->
    <section id="about" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-6">About the Fest</h3>
            <p class="text-gray-600 leading-relaxed max-w-3xl mx-auto">
                <strong>Talents Meet 2025</strong> is a vibrant celebration of art, culture, intellect, and innovation. 
                Organized by our institution, it brings together students from various colleges to showcase their 
                unique abilities and compete in a wide range of events — from cultural performances to technical challenges.
            </p>
        </div>
    </section>

    <!-- 🌟 Events Section -->
    <section id="events" class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-8">Fest Highlights</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h4 class="font-semibold text-xl text-blue-700 mb-2">🎤 Cultural Events</h4>
                    <p class="text-gray-600">Dance, music, drama, and art — where creativity takes center stage.</p>
                </div>
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h4 class="font-semibold text-xl text-indigo-700 mb-2">💻 Technical Competitions</h4>
                    <p class="text-gray-600">Coding, robotics, and innovative projects from top student minds.</p>
                </div>
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <h4 class="font-semibold text-xl text-green-700 mb-2">🏆 Prize Distribution</h4>
                    <p class="text-gray-600">Recognizing excellence and awarding brilliance across every category.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 🌟 Contact Section -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-6">Contact Us</h3>
            <p class="text-gray-600 mb-4">Have questions about the event? Get in touch with our organizing team!</p>
            <p class="text-gray-700">
                📧 <a href="mailto:info@talentsmeet.com" class="text-blue-600 hover:underline">info@talentsmeet.com</a><br>
                📞 +91 98765 43210
            </p>
        </div>
    </section>

    <!-- 🌟 Footer -->
    <footer class="bg-gray-900 text-gray-400 text-center py-4">
        <p>© {{ date('Y') }} Talents Meet 2025 — All Rights Reserved.</p>
    </footer>

</body>
</html>
