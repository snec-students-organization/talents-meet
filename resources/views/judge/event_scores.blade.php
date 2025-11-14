<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        {{-- Header --}}
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            {{ $event->name }} — <span class="text-blue-700">Stage {{ $event->stage_number }}</span>
        </h1>

        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('judge.dashboard') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-3 py-1 rounded">
                ← Back to Events
            </a>

            <button id="exportPDF"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                Export PDF
            </button>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- No Participants --}}
        @if($registrations->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No participants have been assigned a code letter yet.
            </div>
        @else

            <form action="{{ route('judge.submitMarks', $event->id) }}" method="POST">
                @csrf

                <table id="scoreTable" class="min-w-full bg-white rounded-lg shadow mb-4">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-700">
                            <th class="p-3">#</th>
                            <th class="p-3 text-center">Code Letter</th>
                            <th class="p-3 text-center">UID</th>
                            <th class="p-3 text-center">Score (0–100)</th>
                            <th class="p-3 text-center">Grade</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($registrations as $index => $registration)
                            @php
                                $savedScore = $existingScores[$registration->id]['score'] ?? '';
                                $savedGrade = $existingScores[$registration->id]['grade'] ?? '';
                            @endphp

                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">{{ $index + 1 }}</td>

                                <td class="p-3 font-semibold text-blue-700 text-center">
                                    {{ $registration->code_letter }}
                                </td>

                                <td class="p-3 text-center">
                                    {{ $registration->student->uid ?? '-' }}
                                </td>

                                <td class="p-3 text-center">
                                    <input type="number"
                                           name="scores[{{ $registration->id }}]"
                                           value="{{ $savedScore }}"
                                           min="0" max="100"
                                           class="border rounded w-24 p-1 text-center">
                                </td>

                                <td class="p-3 text-center">
                                    <select name="grades[{{ $registration->id }}]"
                                            class="border rounded p-1 text-sm">

                                        <option value="">Select</option>

                                        @foreach(['A','B','C'] as $grade)
                                            <option value="{{ $grade }}"
                                                {{ $savedGrade == $grade ? 'selected' : '' }}>
                                                {{ $grade }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        💾 Save Scores
                    </button>
                </div>

            </form>

        @endif
    </div>

    {{-- PDF Export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.min.js"></script>

    <script>
        document.getElementById('exportPDF').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;

            const doc = new jsPDF('p', 'pt', 'a4');

            doc.setFontSize(14);
            doc.text("{{ $event->name }} — Stage {{ $event->stage_number }}", 40, 40);

            doc.autoTable({
                html: '#scoreTable',
                startY: 60,
                styles: { fontSize: 9, cellPadding: 3 },
                headStyles: { fillColor: [41, 128, 185], textColor: [255,255,255] },
                theme: 'striped'
            });

            doc.save("{{ Str::slug($event->name) }}_scores.pdf");
        });
    </script>

</x-app-layout>
