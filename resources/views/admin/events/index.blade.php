<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            All Events by Stream, Stage Type, and Category
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @php
            $streams = ['sharia', 'sharia_plus', 'she', 'she_plus', 'life', 'life_plus', 'bayyinath'];
            $categories = ['A', 'B', 'C', 'D'];
        @endphp

        @foreach($streams as $stream)
            @php
                $streamEvents = $events->where('stream', $stream);
                $stageEvents = $streamEvents->where('stage_type', 'stage');
                $nonStageEvents = $streamEvents->where('stage_type', 'non_stage');
            @endphp

            @if($streamEvents->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-semibold capitalize text-blue-700 mb-4 border-b pb-2">
                        {{ str_replace('_', ' ', $stream) }} Stream
                    </h2>

                    {{-- Stage Events Table --}}
                    @if($stageEvents->isNotEmpty())
                        <div class="mb-8" x-data="{ selectedCategory: '' }">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-xl font-semibold text-green-700">🎭 Stage Events</h3>
                                <div class="flex items-center space-x-2">
                                    <select x-model="selectedCategory" class="border rounded px-2 py-1">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>

                                    <button onclick="exportTableToExcel('{{ $stream }}-stage')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Excel
                                    </button>
                                    <button onclick="exportTableToPDF('{{ $stream }}-stage')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        PDF
                                    </button>
                                </div>
                            </div>

                            <table id="table-{{ $stream }}-stage" class="min-w-full bg-white rounded-lg shadow mb-4">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-700">
                                        <th class="p-3">#</th>
                                        <th class="p-3">Event Name</th>
                                        <th class="p-3">Category</th>
                                        <th class="p-3">Type</th>
                                        <th class="p-3">Level</th>
                                        <th class="p-3">Allowed Streams</th>
                                        <th class="p-3">Max Participants</th>
                                        <th class="p-3">Max Entries / Institution</th>
                                        <th class="p-3">Stage Assignment</th>
                                        <th class="p-3">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stageEvents as $index => $event)
                                        <tr class="border-b hover:bg-gray-50"
                                            x-show="selectedCategory === '' || selectedCategory === '{{ $event->category }}'">
                                            <td class="p-3">{{ $index + 1 }}</td>
                                            <td class="p-3 font-semibold">{{ $event->name }}</td>
                                            <td class="p-3">{{ $event->category }}</td>
                                            <td class="p-3 capitalize">{{ $event->type }}</td>
                                            <td class="p-3 capitalize">{{ $event->level ?? '-' }}</td>
                                            <td class="p-3">
                                                @if($event->allowed_streams)
                                                    @foreach(json_decode($event->allowed_streams) as $allowed)
                                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs mr-1">
                                                            {{ str_replace('_', ' ', ucfirst($allowed)) }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400">All Streams</span>
                                                @endif
                                            </td>
                                            <td class="p-3">{{ $event->max_participants ?? '-' }}</td>
                                            <td class="p-3">{{ $event->max_institution_entries ?? 1 }}</td>

                                            {{-- ✅ Stage Assignment --}}
                                            <td class="p-3">
                                                <form action="{{ route('admin.events.assignStage', $event->id) }}" method="POST" class="flex flex-col space-y-2">
    @csrf
    <div class="flex items-center space-x-2">
        <select name="stage_number" class="border rounded p-1 text-sm">
            <option value="">Select Stage</option>
            @for($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ $event->stage_number == $i ? 'selected' : '' }}>
                    Stage {{ $i }}
                </option>
            @endfor
        </select>
        <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white text-xs px-3 py-1 rounded">
            Assign
        </button>
    </div>

    @if($event->stage_number)
        <span class="text-sm text-green-700 ml-1">
            ✅ Assigned: <strong>Stage {{ $event->stage_number }}</strong>
        </span>
    @endif
</form>

                                            </td>

                                            <td class="p-3">{{ $event->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Non-Stage Events --}}
                    @if($nonStageEvents->isNotEmpty())
                        <div class="mb-8" x-data="{ selectedCategory: '' }">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-xl font-semibold text-purple-700">🧾 Non-Stage Events</h3>
                                <div class="flex items-center space-x-2">
                                    <select x-model="selectedCategory" class="border rounded px-2 py-1">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat }}">{{ $cat }}</option>
                                        @endforeach
                                    </select>

                                    <button onclick="exportTableToExcel('{{ $stream }}-nonstage')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                                        Excel
                                    </button>
                                    <button onclick="exportTableToPDF('{{ $stream }}-nonstage')"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                        PDF
                                    </button>
                                </div>
                            </div>

                            <table id="table-{{ $stream }}-nonstage" class="min-w-full bg-white rounded-lg shadow mb-4">
                                <thead>
                                    <tr class="bg-gray-100 text-left text-gray-700">
                                        <th class="p-3">#</th>
                                        <th class="p-3">Event Name</th>
                                        <th class="p-3">Category</th>
                                        <th class="p-3">Type</th>
                                        <th class="p-3">Level</th>
                                        <th class="p-3">Allowed Streams</th>
                                        <th class="p-3">Max Participants</th>
                                        <th class="p-3">Max Entries / Institution</th>
                                        <th class="p-3">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($nonStageEvents as $index => $event)
                                        <tr class="border-b hover:bg-gray-50"
                                            x-show="selectedCategory === '' || selectedCategory === '{{ $event->category }}'">
                                            <td class="p-3">{{ $index + 1 }}</td>
                                            <td class="p-3 font-semibold">{{ $event->name }}</td>
                                            <td class="p-3">{{ $event->category }}</td>
                                            <td class="p-3 capitalize">{{ $event->type }}</td>
                                            <td class="p-3 capitalize">{{ $event->level ?? '-' }}</td>
                                            <td class="p-3">
                                                @if($event->allowed_streams)
                                                    @foreach(json_decode($event->allowed_streams) as $allowed)
                                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs mr-1">
                                                            {{ str_replace('_', ' ', ucfirst($allowed)) }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400">All Streams</span>
                                                @endif
                                            </td>
                                            <td class="p-3">{{ $event->max_participants ?? '-' }}</td>
                                            <td class="p-3">{{ $event->max_institution_entries ?? 1 }}</td>
                                            <td class="p-3">{{ $event->created_at->format('d M Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
    </div>

    {{-- Excel + PDF Export --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.min.js"></script>

    <script>
        function exportTableToExcel(id) {
            const table = document.getElementById(`table-${id}`);
            const wb = XLSX.utils.table_to_book(table, { sheet: "Events" });
            XLSX.writeFile(wb, `${id}_events.xlsx`);
        }

        function exportTableToPDF(id) {
            const table = document.getElementById(`table-${id}`);
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('l', 'pt', 'a4');
            doc.setFontSize(14);
            doc.text(`Event List - ${id.replace('-', ' ').toUpperCase()}`, 40, 40);
            doc.autoTable({
                html: table,
                startY: 60,
                styles: { fontSize: 8, cellPadding: 3 },
                headStyles: { fillColor: [41, 128, 185], textColor: [255, 255, 255] },
                alternateRowStyles: { fillColor: [245, 245, 245] },
                theme: 'striped'
            });
            doc.save(`${id}_events.pdf`);
        }
    </script>
</x-app-layout>
