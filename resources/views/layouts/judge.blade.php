<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Talents Meet 2025') }} - Judge</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-glow {
            box-shadow: 0 0 20px rgba(79, 70, 229, 0.1);
        }
        .judge-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
    </style>
</head>
<body class="font-sans antialiased h-full flex bg-gray-50 text-slate-900" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-slate-800 sidebar-glow">
        
        <div class="flex h-full flex-col">
            <!-- Brand -->
            <div class="flex h-20 items-center px-8 border-b border-slate-800/50">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="p-2 judge-gradient rounded-xl shadow-lg transform group-hover:scale-110 transition-transform">
                        <span class="text-2xl text-white">⚖️</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-extrabold text-xl tracking-tight text-white">Judge Panel</span>
                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-[0.2em]">Talents Meet 2025</span>
                    </div>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 space-y-2 p-6 overflow-y-auto">
                <div class="mb-4">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Main Menu</p>
                    
                    <x-nav-link-custom href="{{ route('judge.dashboard') }}" active="{{ request()->routeIs('judge.dashboard') }}" icon="🏠">
                        Dashboard
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('judge.scores') }}" active="{{ request()->routeIs('judge.scores') }}" icon="📊">
                        Score List
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('judge.nonstage') }}" active="{{ request()->routeIs('judge.nonstage') }}" icon="🎙️">
                        Non-Stage
                    </x-nav-link-custom>
                </div>

                <div class="pt-6 border-t border-slate-800">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-widest mb-4">Settings</p>
                    
                    <x-nav-link-custom href="{{ route('judge.select_stage') }}" active="{{ request()->routeIs('judge.select_stage') }}" icon="📍">
                        Change Stage
                    </x-nav-link-custom>

                    <x-nav-link-custom href="{{ route('profile.edit') }}" active="{{ request()->routeIs('profile.edit') }}" icon="👤">
                        Profile
                    </x-nav-link-custom>
                </div>
            </nav>

            <!-- User Footer -->
            <div class="p-6 border-t border-slate-800 bg-slate-950/30">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-10 w-10 rounded-full judge-gradient flex items-center justify-center font-bold text-white shadow-inner">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Judge Member</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-lg transition-colors group">
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header -->
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-6 lg:px-10 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex flex-col">
                    <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Current Session</h2>
                    <div class="flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="font-bold text-slate-800">
                            @if(session('judge_stage'))
                                Stage {{ session('judge_stage') }} Active
                            @else
                                No Stage Selected
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-3 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-full text-sm font-semibold border border-indigo-100">
                    <span class="text-base text-indigo-500">📅</span>
                    {{ now()->format('D, M d, Y') }}
                </div>
            </div>
        </header>

        <!-- Dynamic Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-50/50 scroll-smooth">
            <div class="max-w-[1600px] mx-auto p-6 lg:p-10">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
