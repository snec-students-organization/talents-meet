<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            {{ $event->name }} — <span class="text-blue-600">Stage {{ $event->stage_number ?? 'N/A' }}</span>
        </h1>
        <p class="text-gray-600 mb-6">
            <strong>Category:</strong> {{ $event->category }} |
            <strong>Type:</strong> {{ ucfirst($event->type) }} |
            <strong>Stream:</strong> {{ ucfirst($event->stream) }}
        </p>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('stage_admin.events.saveCodes', $event->id) }}" method="POST">
            @csrf
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-xl font-semibold text-gray-700">Participants</h2>
                <button type="button" onclick="exportToPDF()"
                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                    Export to PDF
                </button>
            </div>

            <table id="participantsTable" class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="p-3">#</th>
                        <th class="p-3">UID</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Institution</th>
                        <th class="p-3">Assigned Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($registrations as $index => $reg)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-mono">{{ $reg->student->uid ?? '-' }}</td>
                            <td class="p-3">{{ $reg->student->name ?? '-' }}</td>
                            <td class="p-3">{{ $reg->institution->name ?? '-' }}</td>
                            <td class="p-3">
                                <input type="text" name="codes[{{ $reg->id }}]" value="{{ $reg->code_letter }}"
                                       class="border rounded px-2 py-1 w-24 text-center uppercase"
                                       placeholder="A" maxlength="5">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                    Save Codes
                </button>
            </div>
        </form>
    </div>

    {{-- jsPDF for export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.min.js"></script>

    <script>
        function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'a4');

            // Title
            doc.setFontSize(16);
            doc.text("{{ $event->name }} — Stage {{ $event->stage_number ?? 'N/A' }}", 40, 40);
            doc.setFontSize(12);
            doc.text("Category: {{ $event->category }} | Stream: {{ ucfirst($event->stream) }} | Type: {{ ucfirst($event->type) }}", 40, 60);

            // Table
            doc.autoTable({
                html: '#participantsTable',
                startY: 80,
                styles: { fontSize: 10, cellPadding: 4 },
                headStyles: { fillColor: [52, 152, 219] },
            });

            doc.save("{{ Str::slug($event->name) }}_participants.pdf");
        }
    </script>
</x-app-layout>
