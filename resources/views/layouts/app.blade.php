<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Talents Meet 2025') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex flex-col">

    <!-- 🌟 Top Navbar -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left side (Logo / Title) -->
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8">
                    <h1 class="font-semibold text-lg text-gray-800">Talents Meet 2025</h1>
                </div>

                <!-- Right side (User + Logout) -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 text-sm">
                        👋 {{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-sm font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- 🌟 Main Section -->
    <div class="flex flex-1">
        <!-- Sidebar (Optional, only if you added it before) -->
        <aside class="hidden md:block w-64 bg-gray-900 text-white">
            <div class="p-4 border-b border-gray-700 text-center">
                <h2 class="text-lg font-semibold">Menu</h2>
            </div>

            <nav class="p-4 space-y-2 text-sm">

                {{-- ADMIN MENU --}}
                @if(Auth::user()->role === 'admin')
                    <a href="/admin/dashboard" class="block py-2 px-3 rounded hover:bg-gray-700">🏠 Dashboard</a>
                    <a href="/admin/events" class="block py-2 px-3 rounded hover:bg-gray-700">🎭 Manage Events</a>
                    <a href="/admin/students" class="block py-2 px-3 rounded hover:bg-gray-700">👨‍🎓 Manage Students</a>
                    <a href="/admin/results" class="block py-2 px-3 rounded hover:bg-gray-700">🏆 Leaderboard</a>
                @endif

                {{-- JUDGE MENU --}}
                @if(Auth::user()->role === 'judge')
                    <a href="/judge/dashboard" class="block py-2 px-3 rounded hover:bg-gray-700">🏠 Dashboard</a>
                    <a href="/judge/events" class="block py-2 px-3 rounded hover:bg-gray-700">📝 Enter Scores</a>
                    <a href="/judge/assigned" class="block py-2 px-3 rounded hover:bg-gray-700">🎯 Assigned Events</a>
                @endif

                {{-- INSTITUTION MENU --}}
                @if(Auth::user()->role === 'institution')
                    <a href="/institution/dashboard" class="block py-2 px-3 rounded hover:bg-gray-700">🏫 Dashboard</a>
                    <a href="/institution/participants" class="block py-2 px-3 rounded hover:bg-gray-700">👨‍🎓 My Students</a>
                    <a href="/institution/results" class="block py-2 px-3 rounded hover:bg-gray-700">🏆 Results</a>
                @endif

                {{-- STAGE ADMIN MENU --}}
                @if(Auth::user()->role === 'stage_admin')
                    <a href="/stage/dashboard" class="block py-2 px-3 rounded hover:bg-gray-700">🎤 Dashboard</a>
                    <a href="/stage/schedule" class="block py-2 px-3 rounded hover:bg-gray-700">📅 Stage Schedule</a>
                    <a href="/stage/chest-numbers" class="block py-2 px-3 rounded hover:bg-gray-700">🔢 Chest Numbers</a>
                @endif

                {{-- STUDENT MENU --}}
                @if(Auth::user()->role === 'student')
                    <a href="/student/dashboard" class="block py-2 px-3 rounded hover:bg-gray-700">🎓 Dashboard</a>
                    <a href="/student/events" class="block py-2 px-3 rounded hover:bg-gray-700">🎭 My Events</a>
                    <a href="/student/certificates" class="block py-2 px-3 rounded hover:bg-gray-700">📜 Certificates</a>
                @endif
            </nav>
        </aside>

        <!-- Content Area -->
        <main class="flex-1 p-6">
            {{ $slot }}
        </main>
    </div>
</div>
</body>
</html>
