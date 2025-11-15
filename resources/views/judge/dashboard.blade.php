<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Judge Dashboard – Stage {{ $stage }}
            </h1>

            <a href="{{ route('judge.select_stage') }}"
               class="text-sm text-blue-600 underline">
                Change Stage
            </a>
        </div>

        {{-- SCORE LIST BUTTON --}}
        <div class="mb-6">
            <a href="{{ route('judge.scores') }}"
               class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded shadow">
                📊 View All Scores (Score List)
            </a>
        </div>

        {{-- EVENT LIST --}}
        <div class="bg-white shadow rounded-md p-4">
            <h2 class="text-xl font-semibold mb-4">Events Assigned to You</h2>

            @if($events->isEmpty())
                <p class="text-gray-600">No events assigned for this stage.</p>
            @else
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-left">Event</th>
                            <th class="p-3 text-left">Category</th>
                            <th class="p-3 text-left">Type</th>
                            <th class="p-3 text-left">Stream</th>
                            <th class="p-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-semibold">
                                    {{ $event->name }}
                                </td>

                                <td class="p-3">{{ $event->category }}</td>
                                <td class="p-3 capitalize">{{ $event->type }}</td>
                                <td class="p-3 capitalize">
                                    {{ str_replace('_',' ',$event->stream) }}
                                </td>

                                <td class="p-3 text-center">
                                    <a href="{{ route('judge.view_event', $event->id) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">
                                        📝 Score Event
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
        <div class="mb-6">
    <a href="{{ route('judge.nonstage') }}"
       class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded shadow">
        🎧 Score Non-Stage Events
    </a>
</div>


    </div>
</x-app-layout>
