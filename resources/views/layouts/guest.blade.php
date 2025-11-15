<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Talents Meet') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,600,700">

    <!-- App Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- SIMPLE PUBLIC NAVBAR --}}
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- Logo -->
            <a href="/" class="text-2xl font-bold text-indigo-700">
                Talents Meet
            </a>

            <nav class="flex items-center gap-6">

                <a href="/" class="text-gray-700 hover:text-indigo-700">Home</a>

                <a href="/results/sharia" class="text-gray-700 hover:text-indigo-700">
                    Results
                </a>

                @guest
                    <a href="/login"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
                        Login
                    </a>
                @endguest

                @auth
                    <a href="/redirect-dashboard"
                       class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded">
                        Dashboard
                    </a>
                @endauth

            </nav>

        </div>
    </header>

    {{-- PAGE CONTENT --}}
    <main>
        {{ $slot }}
    </main>

</body>
</html>
