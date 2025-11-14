<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Scores List — Stage {{ session('judge_stage') }}
        </h1>

        <a href="{{ route('judge.dashboard') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded mb-4 inline-block">
            ← Back to Dashboard
        </a>

        @if($events->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No events found for your stage.
            </div>
        @else

            @foreach($events as $event)
                @php
                    $eventScores = $scores->where('event_id', $event->id);

                    // Auto Rank Calculation
                    $ranked = $eventScores->sortByDesc('score')->values();

                    foreach ($ranked as $index => $item) {
                        $item->rank = $index + 1;
                    }
                @endphp

                <div class="bg-white shadow-md rounded-lg mb-8">
                    <div class="bg-blue-600 text-white p-3 rounded-t-lg">
                        <h2 class="text-lg font-semibold">
                            {{ $event->name }} (Category: {{ strtoupper($event->category) }})
                        </h2>
                    </div>

                    @if($eventScores->isEmpty())
                        <p class="p-4 text-gray-500 italic">No scores submitted for this event.</p>
                    @else
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-700">
                                    <th class="p-3">Rank</th>
                                    <th class="p-3 text-center">Chest No</th>
                                    <th class="p-3 text-center">UID</th>
                                    <th class="p-3 text-center">Score</th>
                                    <th class="p-3 text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ranked as $item)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-bold text-green-600">
                                            {{ $item->rank }}
                                        </td>
                                        <td class="p-3 text-center">
                                            {{ $item->registration->code_letter }}
                                        </td>
                                        <td class="p-3 text-center">
                                            {{ $item->registration->student->uid ?? '-' }}
                                        </td>
                                        <td class="p-3 text-center">{{ $item->score }}</td>
                                        <td class="p-3 text-center font-bold">{{ $item->grade ?? '-' }}</td>
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
