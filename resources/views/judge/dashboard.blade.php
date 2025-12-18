@extends('layouts.judge')

@section('content')
<div class="space-y-8">
    
    {{-- WELCOME HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Judge Dashboard</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage and score events for Stage <span class="text-indigo-600 font-bold">{{ $stage }}</span></p>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('judge.scores') }}" 
               class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-lg shadow-indigo-200 transition-all font-semibold text-sm transform hover:-translate-y-0.5">
                <span>📊</span> View All Scores
            </a>
            <a href="{{ route('judge.nonstage') }}" 
               class="flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl shadow-sm transition-all font-semibold text-sm transform hover:-translate-y-0.5">
                <span>🎙️</span> Non-Stage Events
            </a>
        </div>
    </div>

    {{-- QUICK STATS (Mockup for aesthetics) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-indigo-100 transition-colors">
            <div class="h-14 w-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">🎭</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Events</p>
                <p class="text-2xl font-extrabold text-slate-900">{{ $events->count() }}</p>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-emerald-100 transition-colors">
            <div class="h-14 w-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">✅</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Scored Events</p>
                <p class="text-2xl font-extrabold text-slate-900">0</p> {{-- This could be dynamic later --}}
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5 group hover:border-orange-100 transition-colors">
            <div class="h-14 w-14 bg-orange-50 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">📍</div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Current Location</p>
                <p class="text-2xl font-extrabold text-slate-900 truncate">Stage {{ $stage }}</p>
            </div>
        </div>
    </div>

    {{-- EVENT LIST --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h2 class="text-xl font-bold text-slate-800">Assigned Events</h2>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Live List</span>
            </div>
        </div>

        <div class="p-0 overflow-x-auto">
            @if($events->isEmpty())
                <div class="p-20 text-center">
                    <div class="text-6xl mb-4">🏜️</div>
                    <h3 class="text-lg font-bold text-slate-800">No events found</h3>
                    <p class="text-slate-500 max-w-xs mx-auto">There are no events assigned to Stage {{ $stage }} at the moment.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Event Details</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Configuration</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($events as $event)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                            {{ substr($event->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-indigo-600 transition-colors">{{ $event->name }}</p>
                                            <p class="text-xs text-slate-400 font-medium">ID: #EV-{{ $event->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase tracking-wider border border-slate-200">
                                        {{ $event->category }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase w-12">Stream:</span>
                                            <span class="text-xs font-semibold text-slate-700 truncate capitalize">{{ str_replace('_',' ',$event->stream) }}</span>
                                        </div>
                                        @if($event->level)
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase w-12">Level:</span>
                                            <span class="text-xs font-semibold text-indigo-600 px-1.5 py-0.5 bg-indigo-50 rounded border border-indigo-100 uppercase tracking-tighter">{{ str_replace('_',' ',$event->level) }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('judge.view_event', $event->id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-600 text-indigo-700 hover:text-white rounded-lg transition-all font-bold text-sm border border-indigo-100 group-hover:shadow-md">
                                        📝 Score Now
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
