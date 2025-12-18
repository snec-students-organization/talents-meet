@extends('layouts.judge')

@section('content')
<div class="space-y-8">
    
    {{-- PAGE HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Non-Stage Events</h1>
            <p class="text-slate-500 mt-1 font-medium">Manage and score events conducted off-stage</p>
        </div>
        
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-bold border border-orange-100 uppercase tracking-wider">
                Off-Stage Registry
            </span>
        </div>
    </div>

    {{-- EVENT LIST --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden text-sm">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h2 class="text-xl font-bold text-slate-800">All Non-Stage Events</h2>
            <div class="flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-orange-500 animate-pulse"></span>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active</span>
            </div>
        </div>

        <div class="p-0 overflow-x-auto">
            @if($events->isEmpty())
                <div class="p-20 text-center">
                    <div class="text-6xl mb-4">🏜️</div>
                    <h3 class="text-lg font-bold text-slate-800">No events found</h3>
                    <p class="text-slate-500 max-w-xs mx-auto">There are no non-stage events available at the moment.</p>
                </div>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Event</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Category</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest">Details</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-500 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($events as $event)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center font-bold text-sm">
                                            {{ substr($event->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-orange-600 transition-colors">{{ $event->name }}</p>
                                            <p class="text-xs text-slate-400 font-medium capitalize">{{ $event->type }}</p>
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
                                            <span class="text-[10px] font-bold text-slate-400 uppercase w-12 text-right">Stream:</span>
                                            <span class="text-xs font-semibold text-slate-600 capitalize">{{ str_replace('_', ' ', $event->stream) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase w-12 text-right">Level:</span>
                                            @if(in_array($event->stream, ['sharia','she']))
                                                <span class="text-xs font-semibold text-indigo-600 uppercase">{{ $event->level ? str_replace('_',' ', $event->level) : 'General' }}</span>
                                            @else
                                                <span class="text-xs font-medium text-slate-400 italic">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('judge.nonstage.event', $event->id) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 hover:bg-orange-600 text-orange-700 hover:text-white rounded-lg transition-all font-bold text-sm border border-orange-100 group-hover:shadow-md">
                                        🎧 Score Event
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
