@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Event Management</h1>
            <p class="text-slate-500">Overview of all events categorized by stream and stage type.</p>
        </div>

        {{-- CREATE EVENT BUTTON --}}
        <a href="{{ route('admin.events.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 
                  text-white text-sm font-semibold rounded-lg shadow-sm transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" 
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v16m8-8H4"/>
            </svg>
            Create Event
        </a>
    </div>

    {{-- SUCCESS MESSAGE --}}
    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200 text-sm font-medium">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- NAVIGATION TABS --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('admin.events.index') }}"
               class="{{ !request('type') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All Events
            </a>
            <a href="{{ route('admin.events.index', ['type' => 'stage']) }}"
               class="{{ request('type') == 'stage' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Stage Events
            </a>
            <a href="{{ route('admin.events.index', ['type' => 'non_stage']) }}"
               class="{{ request('type') == 'non_stage' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Off-Stage Events
            </a>
        </nav>
    </div>

    @php
        $streams = ['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath'];
        $categories = ['A','B','C','D'];
    @endphp

    <div class="space-y-8">
    @foreach($streams as $stream)
        @php
            $streamEvents = $events->where('stream',$stream);
            $stageEvents  = $streamEvents->where('stage_type','stage');
            $nonStage     = $streamEvents->where('stage_type','non_stage');
        @endphp

        @if($streamEvents->isNotEmpty())

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- STREAM HEADER --}}
            <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white text-lg font-bold uppercase tracking-wider">
                    {{ str_replace('_',' ',$stream) }} Stream
                </h3>
                <span class="bg-white/10 text-white text-xs px-2 py-1 rounded-md">
                    {{ $streamEvents->count() }} Events
                </span>
            </div>

            <div class="p-6 space-y-8">

                {{-- 🎭 STAGE EVENTS --}}
                @if($stageEvents->isNotEmpty())
                <div x-data="{ selectedCategory:'' }">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                        <h4 class="text-lg font-bold text-slate-700 flex items-center gap-2">
                            <span class="bg-purple-100 text-purple-600 p-1.5 rounded-lg text-xl">🎭</span>
                            Stage Events
                        </h4>

                        <div class="flex items-center gap-2">
                            <select x-model="selectedCategory"
                                class="text-sm border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>

                            <button class="px-3 py-2 bg-white border border-gray-300 text-slate-600 rounded-lg text-sm"
                                onclick="exportTableToExcel('{{ $stream }}-stage')">
                                Excel
                            </button>

                            <button class="px-3 py-2 bg-white border border-gray-300 text-red-600 rounded-lg text-sm"
                                onclick="exportTableToPDF('{{ $stream }}-stage')">
                                PDF
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table id="table-{{ $stream }}-stage" class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Event Name</th>
                                    <th class="px-4 py-3">Cat</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Level</th>
                                    <th class="px-4 py-3">Allowed</th>
                                    <th class="px-4 py-3">Slot</th>
                                    <th class="px-4 py-3">Per Inst.</th>
                                    <th class="px-4 py-3">Assign Stage</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y bg-white">
                                @foreach($stageEvents as $i=>$event)
                                <tr x-show="selectedCategory==='' || selectedCategory==='{{ $event->category }}'">
                                    <td class="px-4 py-3">{{ $i+1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $event->name }}</td>
                                    <td class="px-4 py-3">{{ $event->category }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $event->type }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $event->level ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs">Allowed</td>
                                    <td class="px-4 py-3">{{ $event->max_participants ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $event->max_institution_entries ?? 1 }}</td>
                                    <td class="px-4 py-3">
                                        <form action="{{ route('admin.events.assignStage',$event->id) }}" method="POST">
                                            @csrf
                                            <select name="stage_number" class="text-xs border-gray-300 rounded">
                                                <option value="">Select</option>
                                                @for($s=1;$s<=5;$s++)
                                                    <option value="{{ $s }}" {{ $event->stage_number==$s?'selected':'' }}>
                                                        Stage {{ $s }}
                                                    </option>
                                                @endfor
                                            </select>
                                            <button class="bg-indigo-600 text-white px-2 py-1 rounded text-xs ml-1">
                                                Save
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- 📝 NON-STAGE EVENTS --}}
                @if($nonStage->isNotEmpty())
                <div x-data="{ selectedCategory:'' }">

                    <h4 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                        <span class="bg-amber-100 text-amber-600 p-1.5 rounded-lg text-xl">📝</span>
                        Non-Stage Events
                    </h4>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table id="table-{{ $stream }}-nonstage" class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-amber-50">
                                <tr>
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Event Name</th>
                                    <th class="px-4 py-3">Cat</th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">Level</th>
                                    <th class="px-4 py-3">Allowed</th>
                                    <th class="px-4 py-3">Slot</th>
                                    <th class="px-4 py-3">Per Inst.</th>
                                    <th class="px-4 py-3">Created</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y bg-white">
                                @foreach($nonStage as $i=>$event)
                                <tr>
                                    <td class="px-4 py-3">{{ $i+1 }}</td>
                                    <td class="px-4 py-3 font-medium">{{ $event->name }}</td>
                                    <td class="px-4 py-3">{{ $event->category }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $event->type }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $event->level ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs">Allowed</td>
                                    <td class="px-4 py-3">{{ $event->max_participants ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $event->max_institution_entries ?? 1 }}</td>
                                    <td class="px-4 py-3 text-xs">{{ $event->created_at->format('d M Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endif
    @endforeach
    </div>

</div>

{{-- EXPORT SCRIPTS --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.min.js"></script>

<script>
function exportTableToExcel(id){
    let table=document.getElementById(`table-${id}`);
    let wb=XLSX.utils.table_to_book(table,{sheet:"Events"});
    XLSX.writeFile(wb,`${id}_events.xlsx`);
}

function exportTableToPDF(id){
    let table=document.getElementById(`table-${id}`);
    let { jsPDF } = window.jspdf;
    let doc=new jsPDF('l','pt','a4');
    doc.autoTable({ html: table, startY:40 });
    doc.save(`${id}_events.pdf`);
}
</script>
@endsection
