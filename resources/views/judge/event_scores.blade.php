<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        {{-- PAGE HEADER --}}
        <div class="bg-white shadow rounded-lg p-5 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">
                {{ $event->name }}
            </h1>

            <div class="mt-2 text-gray-700 grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                <div><strong>Stage:</strong> {{ $event->stage_number ?? '—' }}</div>
                <div><strong>Category:</strong> {{ $event->category }}</div>
                <div><strong>Type:</strong> {{ ucfirst($event->type) }}</div>
                <div><strong>Stage Type:</strong> {{ ucfirst(str_replace('_',' ', $event->stage_type)) }}</div>
                <div><strong>Stream:</strong> {{ ucwords(str_replace('_', ' ', $event->stream)) }}</div>

                <div>
                    <strong>Level:</strong>
                    @if($event->level)
                        {{ ucwords(str_replace('_',' ', $event->level)) }}
                    @else
                        —
                    @endif
                </div>
            </div>
        </div>

        {{-- BUTTONS --}}
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

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- NO PARTICIPANTS --}}
        @if($registrations->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No participants have been assigned a code letter yet.
            </div>
        @else

            {{-- SCORE FORM --}}
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
                                {{-- INDEX --}}
                                <td class="p-3">{{ $index + 1 }}</td>

                                {{-- CODE LETTER --}}
                                <td class="p-3 font-semibold text-blue-700 text-center">
                                    {{ $registration->code_letter }}
                                </td>

                                {{-- STUDENT UID --}}
                                <td class="p-3 text-center">
                                    {{ $registration->student->uid ?? '-' }}
                                </td>

                                {{-- SCORE INPUT --}}
                                <td class="p-3 text-center">
                                    <input type="number"
                                           name="scores[{{ $registration->id }}]"
                                           value="{{ $savedScore }}"
                                           min="0" max="100"
                                           class="border rounded w-24 p-1 text-center">
                                </td>

                                {{-- GRADE SELECT --}}
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

                {{-- SUBMIT --}}
                <div class="text-right">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        💾 Save Scores
                    </button>
                </div>
            </form>
        @endif
    </div>

    {{-- PDF EXPORT --}}
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
