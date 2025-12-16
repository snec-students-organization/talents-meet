<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Stage Admin | {{ config('app.name', 'Talents Meet') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-full flex flex-col bg-slate-50 text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <span class="text-2xl pt-1">🎤</span>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg text-slate-800 leading-none">Stage Admin Portal</span>
                        <span class="text-xs font-medium text-slate-500">Talents Meet 2025</span>
                    </div>
                </div>

                <!-- Stage Indicator & User Dropdown -->
                <div class="flex items-center gap-4">
                    @if(session('stage_number'))
                        <div class="hidden md:flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-semibold border border-indigo-100">
                            <span>Stage {{ session('stage_number') }}</span>
                        </div>
                    @endif

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" 
                                class="flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-indigo-600 transition-colors">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" style="display: none;"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Layout -->
    <div class="flex flex-1 pt-16 h-screen overflow-hidden">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-900 hidden md:flex flex-col border-r border-gray-800">
            <div class="p-4 space-y-1">
                <x-nav-link-custom href="{{ route('stage_admin.dashboard') }}" 
                                 active="{{ request()->routeIs('stage_admin.dashboard') }}" 
                                 icon="📊">
                    Dashboard
                </x-nav-link-custom>

                @if(session('stage_number'))
                <div class="pt-4 mt-4 border-t border-slate-700">
                    <div class="px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                        Actions
                    </div>
                    <a href="{{ route('stage_admin.select_stage') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-slate-400 rounded-md hover:bg-slate-800 hover:text-white transition-colors">
                        <span>🔄</span> Change Stage
                    </a>
                </div>
                @endif
            </div>
        </aside>

        <!-- Page Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
