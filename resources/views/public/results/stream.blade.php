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

        {{-- If no results --}}
        @if(empty($rows) || empty($eventRanks))
            <p class="text-gray-500">Results not published yet.</p>
        @endif

        @php
            $hasLevels = in_array($stream, ['sharia', 'she']);
            $levels = [
                'sanaviyya_ulya' => 'Sanaviyya Ulya',
                'bakalooriyya' => 'Bakalooriyya',
                'majestar' => 'Majestar'
            ];
        @endphp


        {{-- ●●● STREAMS WITH LEVELS ●●● --}}
        @if($hasLevels)

            @foreach($levels as $levelKey => $levelName)

                @php
                    $levelEvents = $eventList->where('level', $levelKey);

                    if ($levelEvents->isEmpty()) continue;

                    $displayEvents = $eventId
                        ? $levelEvents->where('id', $eventId)
                        : $levelEvents;
                @endphp

                <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-4">
                    🎓 {{ $levelName }}
                </h2>

                @foreach($displayEvents as $event)

                    <h3 class="text-xl font-semibold mt-6 mb-2">
                        {{ $event->name }} ({{ $event->category }})
                    </h3>

                    @php
                        $eventData = $eventRanks[$event->id] ?? [];
                    @endphp

                    @if(empty($eventData))
                        <p class="text-gray-500 mb-4">No results for this event.</p>
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

            @endforeach


        {{-- ●●● STREAMS WITHOUT LEVELS (NORMAL MODE) ●●● --}}
        @else

            @php
                $displayEvents = $eventId
                    ? $eventList->where('id', $eventId)
                    : $eventList;
            @endphp

            @foreach($displayEvents as $event)

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

        @endif

    </div>
</x-guest-layout>
