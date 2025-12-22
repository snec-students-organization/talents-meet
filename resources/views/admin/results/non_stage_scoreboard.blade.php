@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6 text-gray-800">
            Non-Stage Scoreboard — {{ ucwords(str_replace('_',' ', $stream)) }} Stream
        </h1>

        {{-- FILTER BAR --}}
        <form method="GET" class="flex space-x-4 mb-6">
            <select name="event_id" class="border rounded p-2">
                <option value="">All Events</option>
                @foreach($eventList as $ev)
                    <option value="{{ $ev->id }}" {{ $ev->id == $eventId ? 'selected' : '' }}>
                        {{ $ev->name }} ({{ $ev->category }})
                    </option>
                @endforeach
            </select>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        {{-- ⭐ EVENT DETAILS BOX --}}
        @if($selectedEvent)
            <div class="bg-white shadow rounded-lg p-5 border-l-4 border-blue-600 mb-6">
                <h2 class="text-xl font-bold text-gray-800">{{ $selectedEvent->name }}</h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 text-sm">

                    <div>
                        <p class="text-gray-500">Category</p>
                        <p class="font-semibold">{{ $selectedEvent->category }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">Stream</p>
                        <p class="font-semibold capitalize">
                            {{ str_replace('_',' ', $selectedEvent->stream) }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Type</p>
                        <p class="font-semibold capitalize">{{ $selectedEvent->type }}</p>
                    </div>

                    {{-- SHOW LEVEL IF AVAILABLE --}}
                    @if($selectedEvent->level)
                        <div>
                            <p class="text-gray-500">Level</p>
                            <p class="font-semibold capitalize">
                                {{ str_replace('_',' ', $selectedEvent->level) }}
                            </p>
                        </div>
                    @endif

                </div>
            </div>
        @endif


        {{-- SCOREBOARD TABLE --}}
        <div class="bg-white shadow rounded p-4">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2">Rank</th>
                        <th class="p-2">Event</th>
                        <th class="p-2">Category</th>
                        <th class="p-2">UID</th>
                        <th class="p-2">Name</th>
                        <th class="p-2">Institution</th>
                        <th class="p-2">Score</th>
                        <th class="p-2">Grade</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($scoreboard as $row)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-2 font-bold text-blue-700">{{ $row->rank }}</td>
                            <td class="p-2">{{ $row->event_name }}</td>
                            <td class="p-2">{{ $row->category }}</td>
                            <td class="p-2">{{ $row->uid }}</td>
                            <td class="p-2">{{ $row->student_name }}</td>
                            <td class="p-2">{{ $row->institution_name }}</td>
                            <td class="p-2 font-semibold">{{ number_format($row->avg_score, 2) }}</td>
                            <td class="p-2">{{ $row->grade ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center p-4 text-gray-600">
                                No non-stage results available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
