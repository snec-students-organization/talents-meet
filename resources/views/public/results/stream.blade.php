@extends('layouts.public')

@section('content')

    <h1 class="text-4xl font-extrabold mb-8 text-gray-900 tracking-tight">
        🏆 Results – <span class="text-blue-600">{{ ucwords(str_replace('_',' ', $stream)) }}</span> Stream
    </h1>

    {{-- EVENT FILTER --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form method="GET" class="flex flex-col md:flex-row md:items-center gap-4">
            <label class="font-bold text-gray-700">Filter by Event:</label>
            <div class="flex-1 flex gap-3">
                <select name="event_id" onchange="this.form.submit()" class="flex-1 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                    <option value="">All Events</option>
                    @foreach($eventList as $e)
                        <option value="{{ $e->id }}" {{ $eventId == $e->id ? 'selected' : '' }}>
                            {{ $e->name }} ({{ $e->category }})
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2 bg-slate-800 text-white font-bold rounded-lg hover:bg-slate-900 transition-colors">
                    Filter
                </button>
            </div>
        </form>
    </div>

    {{-- If no results --}}
    @if(empty($eventRanks))
        <div class="bg-white p-12 rounded-2xl shadow-sm border border-gray-200 text-center">
            <div class="text-6xl mb-4">📢</div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Results not published yet</h2>
            <p class="text-gray-500">Please check back later for updates on the {{ ucwords(str_replace('_',' ', $stream)) }} stream.</p>
        </div>
    @endif

    @php
        $events = $eventId ? $eventList->where('id', $eventId) : $eventList;
    @endphp

    @foreach($events as $event)
        <div class="mb-12">
            {{-- EVENT HEADER --}}
            <div class="flex items-center justify-between mb-4 px-2">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">🎭</span>
                    {{ $event->name }}
                </h2>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase tracking-widest border border-gray-200">
                        {{ $event->category }}
                    </span>
                    @if($event->level)
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full uppercase tracking-widest border border-blue-100">
                            {{ str_replace('_',' ', $event->level) }}
                        </span>
                    @endif
                </div>
            </div>

            @php
                $eventData = $eventRanks[$event->id] ?? [];
            @endphp

            @if(empty($eventData))
                <div class="bg-gray-50 p-8 rounded-xl border border-dashed border-gray-300 text-center mx-2">
                    <p class="text-gray-400 font-medium italic text-sm">Results for this event are not yet available.</p>
                </div>
                @continue
            @endif

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100 mx-2">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 border-b border-gray-100">
                            <tr>
                                <th class="p-4 text-left font-bold text-gray-500 uppercase tracking-tight text-[11px]">Rank</th>
                                <th class="p-4 text-left font-bold text-gray-500 uppercase tracking-tight text-[11px]">Institution</th>
                                <th class="p-4 text-left font-bold text-gray-500 uppercase tracking-tight text-[11px]">UID</th>
                                <th class="p-4 text-left font-bold text-gray-500 uppercase tracking-tight text-[11px]">Participant Name</th>
                                <th class="p-4 text-left font-bold text-gray-500 uppercase tracking-tight text-[11px]">Grade</th>
                                <th class="p-4 text-right font-bold text-gray-500 uppercase tracking-tight text-[11px]">Points</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @foreach($eventData as $item)
                                @if($item->rank > 3) @continue @endif
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="p-4">
                                        @if($item->rank == 1)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                                🥇 1st PLACE
                                            </span>
                                        @elseif($item->rank == 2)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-slate-100 text-slate-800 border border-slate-200 shadow-sm">
                                                🥈 2nd PLACE
                                            </span>
                                        @elseif($item->rank == 3)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black bg-orange-100 text-orange-800 border border-orange-200 shadow-sm">
                                                🥉 3rd PLACE
                                            </span>
                                        @else
                                            <span class="font-bold text-gray-400 ml-3">{{ $item->rank }}</span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-900">{{ $item->institution->name ?? 'N/A' }}</span>
                                            <span class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">Institution</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded font-mono text-xs font-bold border border-indigo-100">
                                            {{ $item->uid }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-semibold text-gray-700">{{ $item->name }}</td>
                                    <td class="p-4 text-center">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-black {{ $item->grade == 'A' ? 'bg-emerald-100 text-emerald-800' : ($item->grade == 'B' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600') }}">
                                            {{ $item->grade ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <span class="text-xl font-black text-slate-900">{{ number_format($item->points, 0) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

@endsection
