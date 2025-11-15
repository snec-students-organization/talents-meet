<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <h1 class="text-2xl font-bold mb-4">
            Score – {{ $event->name }} (Non-Stage)
        </h1>

        @if(session('success'))
            <div class="p-3 bg-green-200 text-green-800 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('judge.nonstage.submit', $event->id) }}">
            @csrf

            <table class="min-w-full bg-white shadow rounded mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">UID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3 text-center">Score</th>
                        <th class="p-3 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $reg)
                        <tr class="border-b">
                            <td class="p-3">{{ $reg->student->uid }}</td>
                            <td class="p-3">{{ $reg->student->name }}</td>
                            <td class="p-3 text-center">
                                <input type="number"
                                       name="scores[{{ $reg->id }}]"
                                       min="0" max="100"
                                       value="{{ $existingScores[$reg->id]['score'] ?? '' }}"
                                       class="w-20 p-1 border rounded text-center">
                            </td>
                            <td class="p-3 text-center">
                                <select name="grades[{{ $reg->id }}]" class="border p-1">
                                    <option value="">Select</option>
                                    @foreach(['A','B','C'] as $g)
                                        <option value="{{ $g }}"
                                            @selected(($existingScores[$reg->id]['grade'] ?? '') == $g)>
                                            {{ $g }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded">
                Save Scores
            </button>
        </form>

    </div>
</x-app-layout>
