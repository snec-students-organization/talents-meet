<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Talents Meet 2025') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-slate-900 antialiased h-full flex flex-col justify-center items-center">

    <div class="w-full max-w-md px-6 py-8">
        
        <div class="text-center mb-6">
            <a href="/" class="inline-block">
                <img src="/logo.png" class="h-20 w-auto mx-auto mb-4">
            </a>
            <h2 class="text-2xl font-bold text-slate-800">Talents Meet <span class="text-blue-600">2025</span></h2>
        </div>

        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
