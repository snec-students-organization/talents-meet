<x-app-layout>
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
            <h2 class="text-2xl font-semibold mt-10 mb-3">
                {{ $event->name }} (Category {{ $event->category }})
            </h2>

            @php
                $eventData = $eventRanks[$event->id] ?? [];
            @endphp

            @if(empty($eventData))
                <p class="text-gray-500 mb-4">No results found for this event.</p>
                @continue
            @endif

            <table class="min-w-full bg-white shadow rounded text-sm mb-10">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3">Rank</th>
                        <th class="p-3">Institution</th>
                        <th class="p-3">UID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Grade</th>
                        <th class="p-3">Points</th>
                    </tr>
                </thead>

                <tbody>
                    @php $rank = 1; @endphp

                    @foreach($eventData as $instId => $item)
                        <tr class="border-b">
                            <td class="p-3 font-bold">{{ $rank++ }}</td>
                            <td class="p-3">{{ $item->institution->name ?? '' }}</td>
                            <td class="p-3">{{ $item->uid }}</td>
                            <td class="p-3">{{ $item->name }}</td>
                            <td class="p-3">{{ $item->category }}</td>
                            <td class="p-3">{{ $item->grade }}</td>
                            <td class="p-3 font-bold">
                                {{ DB::table($stream . '_results')->where('institution_id', $instId)->value('total_points') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endforeach

    </div>
</x-app-layout>
