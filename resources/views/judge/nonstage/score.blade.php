<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        {{-- HEADER --}}
        <h1 class="text-3xl font-bold mb-6">
            Score – {{ $event->name }} (Non-Stage)
        </h1>

        {{-- EVENT INFO BOX --}}
        <div class="bg-white shadow rounded-md p-4 mb-6 border">
            <h2 class="text-xl font-semibold mb-2">{{ $event->name }}</h2>

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


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="p-3 bg-green-200 text-green-800 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif


        {{-- SCORE FORM --}}
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
                        <tr class="border-b hover:bg-gray-50">
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
                                <select name="grades[{{ $reg->id }}]" class="border p-1 rounded">
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
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded shadow">
                💾 Save Scores
            </button>
        </form>

    </div>
</x-app-layout>
