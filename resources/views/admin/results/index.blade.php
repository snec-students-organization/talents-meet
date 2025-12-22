@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6">Results – Admin Panel</h1>

        {{-- STREAM SELECT --}}
        <form method="GET" class="mb-4">
            <label class="font-semibold mr-2">Select Stream:</label>
            <select name="stream" onchange="this.form.submit()" class="border p-2 rounded">
                @foreach($streams as $s)
                    <option value="{{ $s }}" {{ $stream == $s ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_',' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- EVENT FILTER --}}
        <form method="GET" class="mb-6 flex gap-3">
            <input type="hidden" name="stream" value="{{ $stream }}">
            <label class="font-semibold">Filter Event:</label>
            <select name="event_id" onchange="this.form.submit()" class="border p-2 rounded">
                <option value="">All Events</option>
                @foreach($eventList as $e)
                    <option value="{{ $e->id }}" {{ $eventId == $e->id ? 'selected' : '' }}>
                        {{ $e->name }} ({{ $e->category }})
                    </option>
                @endforeach
            </select>
        </form>

        {{-- SHOW EVENTS --}}
        @foreach($events as $event)

            {{-- EVENT INFO BOX --}}
            <div class="bg-white shadow rounded-md p-4 mb-5 border">
                <h2 class="text-2xl font-semibold mb-2">{{ $event->name }}</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">

                    <div>
                        <span class="font-semibold text-gray-700">Stream:</span><br>
                        <span class="capitalize">{{ str_replace('_',' ', $event->stream) }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Category:</span><br>
                        {{ $event->category }}
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Type:</span><br>
                        <span class="capitalize">{{ $event->type }}</span>
                    </div>

                    <div>
                        <span class="font-semibold text-gray-700">Level:</span><br>
                        @if(in_array($event->stream, ['sharia','she']))
                            {{ $event->level ? ucwords(str_replace('_',' ', $event->level)) : '—' }}
                        @else
                            —
                        @endif
                    </div>

                </div>
            </div>

            @php
                $eventData = $eventRanks[$event->id] ?? [];
            @endphp

            @if(empty($eventData))
                <p class="text-gray-500 mb-8">No results found for this event.</p>
                @continue
            @endif

            {{-- RESULTS TABLE --}}
            <table class="min-w-full bg-white shadow rounded text-sm mb-10">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">Rank</th>
                        <th class="p-3">Institution</th>
                        <th class="p-3">UID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Grade</th>

                        {{-- EVENT SCORE ONLY --}}
                        <th class="p-3 font-bold">Event Points</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($eventData as $item)
                        @if($item->rank > 3) @continue @endif {{-- Show only top 3 as requested --}}
                        <tr class="border-b hover:bg-gray-50 transition-colors">
                            <td class="p-3">
                                @if($item->rank == 1)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        🥇 1st
                                    </span>
                                @elseif($item->rank == 2)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                        🥈 2nd
                                    </span>
                                @elseif($item->rank == 3)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                        🥉 3rd
                                    </span>
                                @else
                                    <span class="text-gray-500">{{ $item->rank }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="font-medium text-gray-900">{{ $item->institution->name ?? 'N/A' }}</span>
                            </td>
                            <td class="p-3 font-mono text-indigo-600">{{ $item->uid }}</td>
                            <td class="p-3 text-gray-700">{{ $item->name }}</td>
                            <td class="p-3 text-gray-500 uppercase">{{ $item->category }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $item->grade == 'A' ? 'bg-emerald-100 text-emerald-800' : ($item->grade == 'B' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ $item->grade ?? '—' }}
                                </span>
                            </td>

                            {{-- Event Points --}}
                            <td class="p-3 font-bold text-blue-700 text-right">
                                {{ $item->points }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endforeach

    </div>
@endsection
