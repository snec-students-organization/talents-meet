@extends('layouts.judge')

@section('content')
<div class="space-y-8">
    
    {{-- HEADER WITH BACK BUTTON --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('judge.dashboard') }}" 
               class="h-12 w-12 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-600 hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-100 transition-all shadow-sm group">
                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $event->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                        {{ $event->category }}
                    </span>
                    <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">•</span>
                    <span class="text-slate-500 font-medium text-sm">Event ID: #EV-{{ $event->id }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button id="exportPDF" 
                    class="flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-slate-50 text-rose-600 border border-rose-200 rounded-xl shadow-sm transition-all font-bold text-sm">
                <span>📄</span> Export Scores PDF
            </button>
        </div>
    </div>

    {{-- INFO GRID --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Current Stage</p>
            <p class="text-lg font-extrabold text-slate-900">Stage {{ $event->stage_number ?? '—' }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Event Type</p>
            <p class="text-lg font-extrabold text-slate-900 capitalize">{{ $event->type }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Stream</p>
            <p class="text-lg font-extrabold text-slate-900 capitalize">{{ str_replace('_', ' ', $event->stream) }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Academic Level</p>
            <p class="text-lg font-extrabold text-indigo-600 capitalize">
                {{ $event->level ? str_replace('_',' ', $event->level) : 'General' }}
            </p>
        </div>
    </div>

    {{-- SCORING SECTION --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold text-sm shadow-indigo-200 shadow-lg">📝</div>
                <h2 class="text-xl font-bold text-slate-800">Scoring Sheet</h2>
            </div>
            <div class="bg-emerald-50 text-emerald-700 px-4 py-1.5 rounded-full text-xs font-bold border border-emerald-100 animate-pulse">
                Ready for input
            </div>
        </div>

        <div class="p-0">
            @if(session('success'))
                <div class="m-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-3">
                    <div class="h-6 w-6 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xs">✓</div>
                    {{ session('success') }}
                </div>
            @endif

            @if($registrations->isEmpty())
                <div class="p-20 text-center">
                    <div class="text-6xl mb-4">⏳</div>
                    <h3 class="text-lg font-bold text-slate-800">Waiting for participants</h3>
                    <p class="text-slate-500 max-w-xs mx-auto">Participants have not been assigned code letters for this event yet.</p>
                </div>
            @else
                <form action="{{ route('judge.submitMarks', $event->id) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table id="scoreTable" class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-20">#</th>
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Chest No</th>
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Student UID</th>
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Score (Max 100)</th>
                                    <th class="px-8 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Grade</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($registrations as $index => $registration)
                                    @php
                                        $savedScore = $existingScores[$registration->id]['score'] ?? '';
                                        $savedGrade = $existingScores[$registration->id]['grade'] ?? '';
                                    @endphp
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-8 py-5 text-slate-400 font-bold text-sm">{{ $index + 1 }}</td>
                                        <td class="px-8 py-5 text-center">
                                            <span class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl font-extrabold text-lg border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                {{ $registration->code_letter }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-5 text-center text-slate-500 font-bold text-sm">
                                            {{ $registration->student->uid ?? '-' }}
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <input type="number" 
                                                   name="scores[{{ $registration->id }}]" 
                                                   value="{{ $savedScore }}" 
                                                   min="0" max="100" 
                                                   placeholder="00"
                                                   class="w-24 px-4 py-3 bg-white border-2 border-slate-200 rounded-2xl text-center text-xl font-extrabold text-indigo-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-200">
                                        </td>
                                        <td class="px-8 py-5 text-center">
                                            <div class="relative inline-block w-24 group/select">
                                                <select name="grades[{{ $registration->id }}]" 
                                                        class="w-full pl-4 pr-8 py-3 bg-white border-2 border-slate-200 rounded-2xl text-center font-bold text-slate-700 focus:ring-4 focus:ring-slate-500/10 focus:border-slate-500 transition-all appearance-none cursor-pointer">
                                                    <option value="">—</option>
                                                    @foreach(['A','B','C'] as $grade)
                                                        <option value="{{ $grade }}" {{ $savedGrade == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-8 bg-slate-50/50 flex justify-end">
                        <button type="submit" 
                                class="flex items-center gap-3 px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-extrabold text-lg shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1 active:scale-[0.98] group">
                            <span>💾</span> Save All Scores
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.0/jspdf.plugin.autotable.min.js"></script>

<script>
    document.getElementById('exportPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'pt', 'a4');

        doc.setFontSize(22);
        doc.setTextColor(15, 23, 42); // slate-900
        doc.text("{{ $event->name }}", 40, 60);
        
        doc.setFontSize(10);
        doc.setTextColor(100, 116, 139); // slate-400
        doc.text("Scoring Sheet - Talents Meet 2025 Edition", 40, 80);
        
        doc.setDrawColor(226, 232, 240); // slate-200
        doc.line(40, 95, 550, 95);

        doc.autoTable({
            html: '#scoreTable',
            startY: 120,
            styles: { 
                fontSize: 10, 
                cellPadding: 10,
                font: 'helvetica',
                textColor: [51, 65, 85]
            },
            headStyles: { 
                fillColor: [79, 70, 229], // indigo-600
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                halign: 'center'
            },
            columnStyles: {
                0: { halign: 'center' },
                1: { fontStyle: 'bold', halign: 'center', textColor: [79, 70, 229] },
                2: { halign: 'center' },
                3: { halign: 'center' },
                4: { halign: 'center' }
            },
            theme: 'striped',
            didDrawPage: function (data) {
                // Footer
                doc.setFontSize(8);
                doc.setTextColor(148, 163, 184);
                doc.text("Page " + doc.internal.getNumberOfPages(), data.settings.margin.left, doc.internal.pageSize.height - 20);
            }
        });

        doc.save("{{ Str::slug($event->name) }}_scoring_sheet.pdf");
    });
</script>
@endsection
