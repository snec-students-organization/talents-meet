@extends('layouts.app')

@section('content')
<div class="space-y-10 pb-10">

    {{-- HERO SECTION --}}
    <div class="text-center pt-8 pb-6 border-b border-gray-200">
        {{-- LOGO --}}
        <img src="/logo.png" class="h-24 md:h-28 mx-auto mb-4">

        {{-- TITLE --}}
        <h1 class="text-4xl md:text-5xl font-bold text-slate-800 mb-2">
            Talents Meet <span class="text-blue-600">2025</span>
        </h1>
        <p class="text-lg text-slate-600 font-medium">Second Edition</p>
        
        <p class="mt-4 max-w-2xl mx-auto text-slate-500">
            Kerala’s Premier Multi-Stream Arts & Talent Festival.
        </p>
    </div>

    {{-- STATS SECTION --}}
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-purple-50 rounded-lg text-2xl">🎭</div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ \App\Models\Event::count() }}</div>
                    <div class="text-sm font-semibold text-slate-500 uppercase">Events</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-blue-50 rounded-lg text-2xl">🏫</div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ \App\Models\User::where('role','institution')->count() }}</div>
                    <div class="text-sm font-semibold text-slate-500 uppercase">Institutions</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-amber-50 rounded-lg text-2xl">🧍</div>
                <div>
                    <div class="text-3xl font-bold text-slate-800">{{ \App\Models\Student::count() }}</div>
                    <div class="text-sm font-semibold text-slate-500 uppercase">Participants</div>
                </div>
            </div>

        </div>
    </div>

    {{-- RESULTS SECTION --}}
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-slate-50 border-b border-gray-200 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-800">Stream Results</h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'] as $s)
                        <a href="/results/{{ $s }}" class="block p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-md transition-all text-center group">
                            <span class="block text-sm text-slate-400 uppercase tracking-wider mb-1 text-[10px]">Stream</span>
                            <span class="font-semibold text-slate-700 group-hover:text-blue-600">
                                {{ ucwords(str_replace('_',' ', $s)) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- LOGIN SECTION --}}
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="text-slate-400 uppercase tracking-widest font-semibold text-xs mb-4">Login Portals</h3>
        
        <div class="inline-flex flex-wrap justify-center gap-3">
            @php
                $logins = [
                    ['name' => 'Institution', 'class' => 'bg-blue-600 hover:bg-blue-700 text-white'],
                    ['name' => 'Judge', 'class' => 'bg-emerald-600 hover:bg-emerald-700 text-white'],
                    ['name' => 'Stage Admin', 'class' => 'bg-slate-600 hover:bg-slate-700 text-white'],
                    ['name' => 'Admin', 'class' => 'bg-slate-800 hover:bg-slate-900 text-white'],
                ];
            @endphp
            
            @foreach($logins as $login)
            <a href="/login" class="px-5 py-2.5 rounded-lg font-medium shadow-sm transition-colors {{ $login['class'] }}">
                {{ $login['name'] }} Login
            </a>
            @endforeach
        </div>
    </div>

</div>
@endsection
