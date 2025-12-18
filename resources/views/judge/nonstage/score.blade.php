@extends('layouts.judge')

@section('content')
<div class="space-y-8">
    
    {{-- HEADER WITH BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('judge.nonstage') }}" 
               class="h-12 w-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-orange-600 hover:border-orange-100 transition-all shadow-sm group">
                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $event->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 bg-orange-50 text-orange-700 rounded text-[10px] font-bold uppercase tracking-wider border border-orange-100">
                        Non-Stage Event
                    </span>
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">•</span>
                    <span class="text-slate-500 font-medium text-sm">Event ID: #EV-{{ $event->id }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2 text-right">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Status</span>
            <div class="bg-emerald-50 text-emerald-700 px-4 py-1 rounded-full text-xs font-bold border border-emerald-100">
                Active Assessment
            </div>
        </div>
    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Category</p>
            <p class="text-lg font-extrabold text-slate-900">{{ $event->category }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Event Type</p>
            <p class="text-lg font-extrabold text-slate-900 capitalize">{{ $event->type }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stream</p>
            <p class="text-lg font-extrabold text-slate-900 capitalize">{{ str_replace('_', ' ', $event->stream) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Academic Level</p>
            <p class="text-lg font-extrabold text-indigo-600 capitalize">
                @if(in_array($event->stream, ['sharia','she']))
                    {{ $event->level ? str_replace('_',' ', $event->level) : 'General' }}
                @else
                    N/A
                @endif
            </p>
        </div>
    </div>

    {{-- SCORING SECTION --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 bg-orange-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-orange-200 shadow-lg">🎤</div>
                <h2 class="text-xl font-bold text-slate-800">Scoring Sheet</h2>
            </div>
        </div>

        <div class="p-0">
            @if(session('success'))
                <div class="m-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <div class="h-6 w-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xs">✓</div>
                    {{ session('success') }}
                </div>
            @endif

            @if($registrations->isEmpty())
                <div class="p-20 text-center">
                    <div class="text-6xl mb-4">🏜️</div>
                    <h3 class="text-lg font-bold text-slate-800">No registrations found</h3>
                    <p class="text-slate-500 max-w-xs mx-auto">There are no participants registered for this non-stage event.</p>
                </div>
            @else
                <form action="{{ route('judge.nonstage.submit', $event->id) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Student UID</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Name</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Score (Max 100)</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($registrations as $reg)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-6">
                                            <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">#{{ $reg->student->uid }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <p class="font-bold text-slate-800">{{ $reg->student->name }}</p>
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <input type="number" 
                                                   name="scores[{{ $reg->id }}]" 
                                                   value="{{ $existingScores[$reg->id]['score'] ?? '' }}" 
                                                   min="0" max="100" 
                                                   placeholder="00"
                                                   class="w-24 px-4 py-3 bg-white border-2 border-slate-200 rounded-2xl text-center text-xl font-extrabold text-orange-600 focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 transition-all placeholder:text-slate-200">
                                        </td>
                                        <td class="px-8 py-6 text-center">
                                            <div class="relative inline-block w-24 group/select">
                                                <select name="grades[{{ $reg->id }}]" 
                                                        class="w-full pl-4 pr-8 py-3 bg-white border-2 border-slate-200 rounded-2xl text-center font-bold text-slate-700 focus:ring-4 focus:ring-slate-500/10 focus:border-slate-500 transition-all appearance-none cursor-pointer">
                                                    <option value="">—</option>
                                                    @foreach(['A','B','C'] as $g)
                                                        <option value="{{ $g }}" {{ ($existingScores[$reg->id]['grade'] ?? '') == $g ? 'selected' : '' }}>{{ $g }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-10 bg-slate-50/50 flex justify-end">
                        <button type="submit" 
                                class="flex items-center gap-3 px-10 py-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-3xl font-extrabold text-lg shadow-xl shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] group">
                            <span>💾</span> Save Non-Stage Scores
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
