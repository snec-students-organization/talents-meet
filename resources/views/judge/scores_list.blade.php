<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Scores List — Stage {{ session('judge_stage') }}
        </h1>

        <a href="{{ route('judge.dashboard') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded mb-4 inline-block">
            ← Back to Dashboard
        </a>

        @if($scores->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No scores have been submitted yet.
            </div>
        @else

            @foreach($events as $event)
                <div class="bg-white shadow-md rounded-lg mb-8">
                    <div class="bg-blue-600 text-white p-3 rounded-t-lg">
                        <h2 class="text-lg font-semibold">{{ $event->name }} ({{ strtoupper($event->category) }})</h2>
                    </div>

                    @php
                        $eventScores = $scores->where('event_id', $event->id);
                    @endphp

                    @if($eventScores->isEmpty())
                        <p class="p-4 text-gray-500 italic">No scores submitted for this event.</p>
                    @else
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100 text-left text-gray-700">
                                    <th class="p-3">#</th>
                                    <th class="p-3 text-center">Chest No</th>
                                    <th class="p-3 text-center">Student UID</th>
                                    <th class="p-3 text-center">Score</th>
                                    <th class="p-3 text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($eventScores as $index => $score)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $index + 1 }}</td>
                                        <td class="p-3 text-center">
                                            {{ $score->registration->code_letter }}
                                        </td>
                                        <td class="p-3 text-center">
                                            {{ $score->registration->student->uid ?? '-' }}
                                        </td>
                                        <td class="p-3 text-center">{{ $score->score }}</td>
                                        <td class="p-3 text-center font-bold">{{ $score->grade ?? '-' }}</td>
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
