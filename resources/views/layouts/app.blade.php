<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
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
<body class="font-sans antialiased h-full flex flex-col bg-gray-50 text-slate-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full top-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo / Brand -->
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        <span class="text-2xl pt-1">🌟</span>
                        <div class="flex flex-col">
                            <span class="font-bold text-xl text-slate-800 tracking-tight leading-none">Talents Meet</span>
                            <span class="text-xs font-semibold text-blue-600 uppercase tracking-widest">2025 Edition</span>
                        </div>
                    </a>
                </div>

                <!-- User Dropdown (Desktop) -->
                @auth
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="ml-3 relative" x-data="{ open: false }">
                        <div>
                            <button @click="open = !open" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                                {{ Auth::user()->name }}
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" style="display: none;" class="absolute right-0 z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 py-1">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                {{ __('Profile') }}
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="flex flex-1 pt-16 overflow-hidden">
        <!-- Sidebar (Only if authenticated) -->
        @auth
        <aside class="w-64 bg-slate-900 overflow-y-auto hidden md:flex flex-col flex-shrink-0 border-r border-gray-200">
            <div class="flex flex-col flex-1 pt-5 pb-4">
                <nav class="flex-1 px-3 space-y-1">
                    
                    {{-- ADMIN --}}
                    @if(Auth::user()->role == 'admin')
                        <x-nav-link-custom href="{{ route('admin.dashboard') }}" active="{{ request()->routeIs('admin.dashboard') }}" icon="🏠">
                            Dashboard
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('admin.events.index') }}" active="{{ request()->routeIs('admin.events.*') }}" icon="🎭">
                            Events
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('admin.registrations.index') }}" active="{{ request()->routeIs('admin.registrations.*') }}" icon="📝">
                            Registrations
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('admin.results.index') }}" active="{{ request()->routeIs('admin.results.*') }}" icon="🏆">
                            Results
                        </x-nav-link-custom>
                    @endif

                    {{-- JUDGE --}}
                    @if(Auth::user()->role == 'judge')
                        <x-nav-link-custom href="{{ route('judge.dashboard') }}" active="{{ request()->routeIs('judge.dashboard') }}" icon="🎤">
                            Judge Dashboard
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('judge.scores') }}" active="{{ request()->routeIs('judge.scores') }}" icon="📊">
                            Score List
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('judge.nonstage') }}" active="{{ request()->routeIs('judge.nonstage') }}" icon="📝">
                            Non-Stage
                        </x-nav-link-custom>
                    @endif

                    {{-- INSTITUTION --}}
                    @if(Auth::user()->role == 'institution')
                        <x-nav-link-custom href="{{ route('institution.dashboard') }}" active="{{ request()->routeIs('institution.dashboard') }}" icon="🏫">
                            Dashboard
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('institution.events.index') }}" active="{{ request()->routeIs('institution.events.*') }}" icon="📅">
                            Events
                        </x-nav-link-custom>
                        <x-nav-link-custom href="{{ route('institution.participants') }}" active="{{ request()->routeIs('institution.participants') }}" icon="🆔">
                            Participants
                        </x-nav-link-custom>
                    @endif

                    {{-- STAGE ADMIN --}}
                    @if(Auth::user()->role == 'stage_admin')
                        <x-nav-link-custom href="{{ route('stage_admin.dashboard') }}" active="{{ request()->routeIs('stage_admin.dashboard') }}" icon="🎭">
                            Stage Dashboard
                        </x-nav-link-custom>
                    @endif

                </nav>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto relative z-0 scroll-smooth">
            <div class="container mx-auto px-6 py-10">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
