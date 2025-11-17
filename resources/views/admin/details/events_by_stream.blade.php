@extends('layouts.app')

@section('content')

<style>
    .section-title {
        font-weight: 800;
        color: #013A63;
    }
    .sub-title {
        font-size: 20px;
        font-weight: 700;
        color: #012A4A;
        margin-bottom: 10px;
    }
    .level-title {
        font-size: 16px;
        font-weight: 700;
        color: #0C4A6E;
        margin: 8px 0;
    }
    .tm-table th {
        background: #013A63;
        color: white;
    }
</style>

<div class="container">

    <h2 class="section-title mb-4">Events Categorised by Streams</h2>

    @foreach($events as $stream => $list)

        {{-- ============================= --}}
        {{--   STREAM NAME HEADER           --}}
        {{-- ============================= --}}
        <h3 class="sub-title mt-4">
            {{ strtoupper(str_replace('_',' ', $stream)) }} Stream
        </h3>

        {{-- ================================================================== --}}
        {{--   LOOP:  STAGE + NON-STAGE  (each has level-wise sub-sections)     --}}
        {{-- ================================================================== --}}

        @foreach(['stage' => '🎤 Stage Events', 'non_stage' => '📝 Non-Stage Events'] as $type => $label)

            <div class="card shadow-sm mb-4">

                <div class="card-header 
                    {{ $type == 'stage' ? 'bg-primary text-white' : 'bg-warning' }}">
                    <strong>{{ $label }}</strong>
                </div>

                <div class="card-body">

                    @php
                        $filtered = $list->where('stage_type', $type);

                        // Level groups only for SHARIA & SHE streams
                        $levelsEnabled = in_array($stream, ['sharia','she']);
                        $levels = ['sanaviyya_ulya', 'bakalooriyya', 'majestar'];
                    @endphp

                    {{-- =========================== --}}
                    {{-- IF STREAM HAS LEVEL SYSTEM   --}}
                    {{-- =========================== --}}
                    @if($levelsEnabled)

                        @foreach($levels as $lvl)

                            @php
                                $items = $filtered->where('level', $lvl);
                            @endphp

                            <h5 class="level-title">
                                {{ ucwords(str_replace('_',' ', $lvl)) }}
                            </h5>

                            @include('admin.details.event_table', ['items' => $items])

                        @endforeach

                        {{-- NO LEVEL SECTION --}}
                        @php
                            $noLevelItems = $filtered->where('level', null);
                        @endphp

                        <h5 class="level-title">No Level Assigned</h5>
                        @include('admin.details.event_table', ['items' => $noLevelItems])

                    @else
                        {{-- STREAM WITHOUT LEVEL SYSTEM --}}
                        @include('admin.details.event_table', ['items' => $filtered])
                    @endif
                </div>
            </div>

        @endforeach
    @endforeach

</div>

@endsection
