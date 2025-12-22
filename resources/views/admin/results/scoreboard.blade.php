@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto p-6">

        <h1 class="text-3xl font-extrabold text-gray-800 mb-8 tracking-tight">
            📊 Scoreboard – {{ ucwords(str_replace('_',' ', $stream)) }} Stream
        </h1>

        @php
            $hasLevels = in_array($stream, ['sharia', 'she']);
            $levels = ['sanaviyya_ulya' => 'Sanaviyya Ulya', 'bakalooriyya' => 'Bakalooriyya', 'majestar' => 'Majestar'];
        @endphp


        {{-- IF STREAM HAS LEVELS — SHOW 3 SEPARATE TABLES --}}
        @if($hasLevels)

            @foreach($levels as $levelKey => $levelName)

                @php
                    // Events belonging to this level
                    $levelEvents = $events->where('level', $levelKey);

                    if ($levelEvents->isEmpty()) continue;

                    // Filter matrix by events of this level
                @endphp

                <h2 class="text-2xl font-bold text-gray-900 mt-10 mb-4">
                    🎓 {{ $levelName }}
                </h2>

                <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200 mb-10">

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-gray-800">

                            {{-- HEADER ROW 1 --}}
                            <thead>
                                <tr class="bg-gray-100 border-b">

                                    <th class="p-3 w-48 text-left font-semibold text-gray-700">
                                        Institution
                                    </th>

                                    {{-- Stage header --}}
                                    <th colspan="{{ $levelEvents->where('stage_type','stage')->count() }}"
                                        class="text-center font-bold text-blue-700 border-l border-blue-300 p-3">
                                        🎤 Stage Events
                                    </th>

                                    {{-- Off-stage header --}}
                                    <th colspan="{{ $levelEvents->where('stage_type','non_stage')->count() }}"
                                        class="text-center font-bold text-yellow-700 border-l border-yellow-300 p-3">
                                        📝 Off Stage Events
                                    </th>

                                    <th rowspan="2"
                                        class="p-3 text-center bg-green-200 border-l border-green-400 font-extrabold">
                                        TOTAL
                                    </th>
                                </tr>

                                {{-- HEADER ROW 2 --}}
                                <tr class="bg-gray-50 border-b">

                                    {{-- Stage events --}}
                                    @foreach($levelEvents->where('stage_type','stage') as $event)
                                        <th class="p-2 border-l text-center align-bottom">
                                            <div class="rotate-[-90deg] origin-bottom-left whitespace-nowrap text-xs font-semibold text-blue-800">
                                                {{ $event->name }}
                                            </div>
                                        </th>
                                    @endforeach

                                    {{-- Off-stage events --}}
                                    @foreach($levelEvents->where('stage_type','non_stage') as $event)
                                        <th class="p-2 border-l text-center align-bottom">
                                            <div class="rotate-[-90deg] origin-bottom-left whitespace-nowrap text-xs font-semibold text-yellow-800">
                                                {{ $event->name }}
                                            </div>
                                        </th>
                                    @endforeach

                                </tr>
                            </thead>

                            {{-- BODY --}}
                            <tbody class="divide-y divide-gray-200">
                                @foreach($institutions as $inst)
                                    @php $totalPoints = 0; @endphp

                                    <tr class="hover:bg-gray-50">

                                        {{-- Institution --}}
                                        <td class="p-3 font-semibold text-gray-900 whitespace-nowrap">
                                            {{ $inst->institution_name }}
                                        </td>

                                        {{-- Stage event points --}}
                                        @foreach($levelEvents->where('stage_type','stage') as $event)
                                            @php
                                                $points = $matrix[$inst->institution_id][$event->id] ?? 0;
                                                $totalPoints += $points;
                                            @endphp
                                            <td class="p-3 text-center font-bold text-blue-700">
                                                {{ $points }}
                                            </td>
                                        @endforeach

                                        {{-- Off-stage event points --}}
                                        @foreach($levelEvents->where('stage_type','non_stage') as $event)
                                            @php
                                                $points = $matrix[$inst->institution_id][$event->id] ?? 0;
                                                $totalPoints += $points;
                                            @endphp
                                            <td class="p-3 text-center font-bold text-yellow-700">
                                                {{ $points }}
                                            </td>
                                        @endforeach

                                        {{-- TOTAL --}}
                                        <td class="p-3 text-center font-extrabold bg-green-100 text-green-800">
                                            {{ $totalPoints }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>

            @endforeach


        @else
            {{-- STREAMS WITHOUT LEVELS — USE NORMAL TABLE --}}
            @include('admin.results.scoreboard_default')
        @endif

    </div>
@endsection
