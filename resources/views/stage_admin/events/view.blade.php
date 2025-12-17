<x-stage-admin-layout>
    <div class="max-w-7xl mx-auto p-6">
        
        <div class="mb-8">
            <a href="{{ route('stage_admin.dashboard') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center gap-1 mb-3 transition-colors">
                <span>&larr;</span> Back to Dashboard
            </a>
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $event->name }}</h1>
                    <div class="flex items-center gap-3 mt-2 text-sm text-gray-600">
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-medium">{{ $event->category }}</span>
                        <span>&bull;</span>
                        <span class="capitalize">{{ $event->stream }}</span>
                        <span>&bull;</span>
                        <span class="capitalize">{{ $event->type }}</span>
                        @if($event->stage_number)
                            <span>&bull;</span>
                            <span class="text-indigo-600 font-semibold">Stage {{ $event->stage_number }}</span>
                        @endif
                    </div>
                </div>
                
                <button type="button" onclick="exportToPDF()"
                    class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export PDF
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200 mb-6 flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-700">Participating Students</h2>
                <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $registrations->count() }} Participants</span>
            </div>

            <form action="{{ route('stage_admin.events.saveCodes', $event->id) }}" method="POST">
                @csrf
                <div class="overflow-x-auto">
                    <table id="participantsTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">UID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Institution</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Code</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($registrations as $index => $reg)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-gray-600">{{ $reg->student->uid ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $reg->student->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $reg->institution->name ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="text" name="codes[{{ $reg->id }}]" value="{{ $reg->code_letter }}"
                                               class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md px-3 py-1.5 w-24 text-center uppercase font-bold text-gray-800 shadow-sm transition-all"
                                               placeholder="CODE" maxlength="5">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                        <span>💾</span> Save All Codes
                    </button>
                </div>
            </form>
        </div>
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
            doc.setTextColor(100);
            doc.text("Category: {{ $event->category }} | Stream: {{ ucfirst($event->stream) }}", 40, 60);

            // Table
            doc.autoTable({
                html: '#participantsTable',
                startY: 80,
                styles: { fontSize: 10, cellPadding: 6 },
                headStyles: { fillColor: [79, 70, 229] }, // Indigo 600
                columnStyles: {
                    4: { halign: 'center' } // Center the code column
                },
                didParseCell: function(data) {
                    // For the input field column, if we can read the value via JS, we should.
                    // But jsPDF html parser might just read the input value attribute if set.
                    // The value attribute is set from the database, so it should work for saved codes.
                }
            });

            doc.save("{{ Str::slug($event->name) }}_participants.pdf");
        }
    </script>
</x-stage-admin-layout>
