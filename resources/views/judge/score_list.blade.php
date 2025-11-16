<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        {{-- PAGE TITLE --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Scores List — Stage {{ session('judge_stage') }}
        </h1>

        {{-- BACK BUTTON --}}
        <a href="{{ route('judge.dashboard') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded mb-6 inline-block">
            ← Back to Dashboard
        </a>

        {{-- NO EVENTS --}}
        @if($events->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No events found for your stage.
            </div>
        @else

            {{-- LOOP EVENTS --}}
            @foreach($events as $event)

                @php
                    // Fetch only scores for this event
                    $eventScores = $scores->where('event_id', $event->id);

                    // Rank calculation
                    $ranked = $eventScores->sortByDesc('score')->values();

                    foreach ($ranked as $index => $item) {
                        $item->rank = $index + 1;
                    }
                @endphp

                <div class="bg-white shadow-md rounded-lg mb-10 overflow-hidden">

                    {{-- EVENT HEADER --}}
                    <div class="bg-blue-600 text-white p-4">
                        <h2 class="text-xl font-semibold">{{ $event->name }}</h2>

                        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2 text-sm text-blue-100">
                            <div><strong>Category:</strong> {{ $event->category }}</div>
                            <div><strong>Type:</strong> {{ ucfirst($event->type) }}</div>
                            <div><strong>Stage Type:</strong> {{ ucfirst(str_replace('_', ' ', $event->stage_type)) }}</div>
                            <div><strong>Stream:</strong> {{ ucwords(str_replace('_', ' ', $event->stream)) }}</div>
                            
                            <div>
                                <strong>Level:</strong>
                                @if($event->level)
                                    {{ ucwords(str_replace('_',' ',$event->level)) }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($eventScores->isEmpty())
                        <p class="p-4 text-gray-500 italic">
                            No scores submitted for this event.
                        </p>
                    @else

                        {{-- SCORE TABLE --}}
                        <table class="min-w-full bg-white text-sm">
                            <thead>
                                <tr class="bg-gray-100 text-gray-700">
                                    <th class="p-3 w-20">Rank</th>
                                    <th class="p-3 text-center">Chest No</th>
                                    <th class="p-3 text-center">UID</th>
                                    <th class="p-3 text-center">Score</th>
                                    <th class="p-3 text-center">Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($ranked as $item)

                                    @php
                                        // Rank Badge Colors
                                        $rankColor = match($item->rank) {
                                            1 => 'bg-yellow-300 text-yellow-900',
                                            2 => 'bg-gray-300 text-gray-800',
                                            3 => 'bg-orange-300 text-orange-900',
                                            default => 'bg-blue-100 text-blue-800'
                                        };
                                    @endphp

                                    <tr class="border-b hover:bg-gray-50">

                                        {{-- RANK --}}
                                        <td class="p-3">
                                            <span class="px-3 py-1 rounded text-sm font-bold {{ $rankColor }}">
                                                {{ $item->rank }}
                                            </span>
                                        </td>

                                        {{-- CHEST NO --}}
                                        <td class="p-3 text-center font-semibold text-blue-700">
                                            {{ $item->registration->code_letter }}
                                        </td>

                                        {{-- UID --}}
                                        <td class="p-3 text-center">
                                            {{ $item->registration->student->uid ?? '-' }}
                                        </td>

                                        {{-- SCORE --}}
                                        <td class="p-3 text-center">
                                            {{ $item->score }}
                                        </td>

                                        {{-- GRADE --}}
                                        <td class="p-3 text-center font-bold">
                                            {{ $item->grade ?? '-' }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    @endif

                </div>
            @endforeach

        @endif

    </div>
</x-app-layout>
