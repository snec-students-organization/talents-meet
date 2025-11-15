<x-guest-layout>
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6">
            Results – {{ ucwords(str_replace('_',' ', $stream)) }} Stream
        </h1>

        {{-- EVENT FILTER --}}
        <form method="GET" class="mb-6">
            <label class="font-semibold mr-2">Filter by Event:</label>
            <select name="event_id" onchange="this.form.submit()" class="border p-2 rounded">
                <option value="">All Events</option>
                @foreach($eventList as $e)
                    <option value="{{ $e->id }}" {{ $eventId == $e->id ? 'selected' : '' }}>
                        {{ $e->name }} ({{ $e->category }})
                    </option>
                @endforeach
            </select>
        </form>

        {{-- IF NO ROWS --}}
        @if(empty($rows) || empty($eventRanks))
            <p class="text-gray-500">Results not published yet.</p>
        @endif


        {{-- SHOW ALL EVENTS OR ONLY FILTERED EVENT --}}
        @php
            $displayEvents = $eventId
                ? $eventList->where('id', $eventId)
                : $eventList;
        @endphp

        @foreach($displayEvents as $event)

            {{-- EVENT HEADING --}}
            <h2 class="text-2xl font-semibold mt-10 mb-3">
                {{ $event->name }} ({{ $event->category }})
            </h2>

            @php
                $eventData = $eventRanks[$event->id] ?? [];
            @endphp

            @if(empty($eventData))
                <p class="text-gray-500 mb-4">No results for this event.</p>
                @continue
            @endif

            {{-- EVENT TABLE --}}
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
</x-guest-layout>
