@extends('layouts.judge')

@section('content')
<div class="space-y-8">
    
    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Score Rankings</h1>
            <p class="text-slate-500 mt-1 font-medium">Consolidated rankings for Stage <span class="text-indigo-600 font-bold">{{ session('judge_stage') }}</span></p>
        </div>
        
        <a href="{{ route('judge.dashboard') }}" 
           class="flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-sm transition-all font-semibold text-sm">
            <span>🏠</span> Back to Dashboard
        </a>
    </div>

    {{-- NO EVENTS --}}
    @if($events->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-20 text-center">
            <div class="text-6xl mb-4">🏜️</div>
            <h3 class="text-lg font-bold text-slate-800">No events found</h3>
            <p class="text-slate-500 max-w-xs mx-auto">We couldn't find any events assigned to your stage.</p>
        </div>
    @else

        <div class="space-y-10">
            @foreach($events as $event)
                @php
                    $eventScores = $scores->where('event_id', $event->id);
                    $ranked = $eventScores->sortByDesc('score')->values();
                    foreach ($ranked as $index => $item) {
                        $item->rank = $index + 1;
                    }
                @endphp

                <div class="group">
                    {{-- EVENT CARD --}}
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden transition-all group-hover:shadow-xl group-hover:shadow-indigo-500/5 group-hover:border-indigo-100">
                        
                        {{-- EVENT HEADER --}}
                        <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div class="h-16 w-16 bg-indigo-600 rounded-3xl flex items-center justify-center text-white text-2xl shadow-lg shadow-indigo-200 group-hover:scale-110 transition-transform">
                                    🏆
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-slate-900 leading-tight">{{ $event->name }}</h2>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                                            {{ $event->category }}
                                        </span>
                                        <span class="text-slate-300">•</span>
                                        <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">{{ str_replace('_', ' ', $event->stream) }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-8">
                                <div class="text-center">
                                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Participants</p>
                                    <p class="text-xl font-black text-slate-800">{{ $eventScores->count() }}</p>
                                </div>
                                <div class="h-10 w-px bg-slate-200"></div>
                                <div class="text-center">
                                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Level</p>
                                    <p class="text-xl font-black text-indigo-600">{{ $event->level ? str_replace('_',' ',$event->level) : 'General' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABLE SECTION --}}
                        <div class="p-0">
                            @if($eventScores->isEmpty())
                                <div class="px-10 py-12 text-center">
                                    <p class="text-slate-400 font-bold italic text-sm">No scores have been submitted for this event yet.</p>
                                    <a href="{{ route('judge.view_event', $event->id) }}" class="inline-block mt-4 text-indigo-600 font-bold text-sm hover:underline italic">Click here to start scoring →</a>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr>
                                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest w-24 text-center">Rank</th>
                                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Chest No</th>
                                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Student UID</th>
                                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Score</th>
                                                <th class="px-10 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach($ranked as $item)
                                                @php
                                                    $rankStyles = match($item->rank) {
                                                        1 => 'bg-amber-100 text-amber-700 border-amber-200 ring-4 ring-amber-500/10',
                                                        2 => 'bg-slate-100 text-slate-600 border-slate-200 ring-4 ring-slate-400/10',
                                                        3 => 'bg-orange-100 text-orange-700 border-orange-200 ring-4 ring-orange-500/10',
                                                        default => 'bg-indigo-50 text-indigo-700 border-indigo-100'
                                                    };
                                                    $trophy = match($item->rank) {
                                                        1 => '🥇',
                                                        2 => '🥈',
                                                        3 => '🥉',
                                                        default => ''
                                                    };
                                                @endphp
                                                <tr class="hover:bg-slate-50/50 transition-colors">
                                                    <td class="px-10 py-6 text-center">
                                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-2xl font-black text-lg border {{ $rankStyles }}">
                                                            {{ $trophy ?: $item->rank }}
                                                        </span>
                                                    </td>
                                                    <td class="px-8 py-6 text-center">
                                                        <span class="text-lg font-extrabold text-slate-900">{{ $item->registration->code_letter }}</span>
                                                    </td>
                                                    <td class="px-8 py-6 text-center">
                                                        <span class="text-sm font-bold text-slate-400 italic">#{{ $item->registration->student->uid ?? '-' }}</span>
                                                    </td>
                                                    <td class="px-8 py-6 text-center">
                                                        <div class="inline-flex flex-col">
                                                            <span class="text-2xl font-black text-indigo-600 leading-none">{{ $item->score }}</span>
                                                            <span class="text-[8px] font-bold text-slate-300 uppercase tracking-tighter mt-1">Points</span>
                                                        </div>
                                                    </td>
                                                    <td class="px-10 py-6 text-center">
                                                        <span class="inline-flex items-center justify-center w-12 h-12 bg-white border-2 border-slate-100 rounded-2xl text-xl font-black text-slate-800 shadow-sm">
                                                            {{ $item->grade ?? '—' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

    @endif

</div>
@endsection
