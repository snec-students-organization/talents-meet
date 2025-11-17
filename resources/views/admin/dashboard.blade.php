@extends('layouts.app')

@section('content')

<style>
    .tm-card {
        border: none;
        border-radius: 14px;
        transition: 0.25s;
    }
    .tm-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
    .tm-icon {
        font-size: 40px;
        color: #013A63;
    }
    .section-title {
        font-weight: 800;
        color: #013A63;
        letter-spacing: .5px;
    }
</style>

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="text-center mb-4">
        <h1 class="section-title">Admin Dashboard</h1>
        <p class="text-muted">Manage events, registrations, results & more</p>
    </div>

    {{-- QUICK STATS --}}
<div class="row g-4 mb-5">

    {{-- ⭐ TOTAL EVENTS --}}
    <div class="col-md-4">
        <a href="{{ route('admin.details.events') }}" class="text-decoration-none text-dark">
            <div class="card tm-card p-4 text-center shadow-sm">
                <div class="tm-icon mb-2">🎭</div>
                <h4 class="fw-bold">Total Events</h4>
                <p class="fs-4 text-primary">{{ \App\Models\Event::count() }}</p>
            </div>
        </a>
    </div>

    {{-- ⭐ TOTAL INSTITUTIONS --}}
    <div class="col-md-4">
        <a href="{{ route('admin.details.institutions') }}" class="text-decoration-none text-dark">
            <div class="card tm-card p-4 text-center shadow-sm">
                <div class="tm-icon mb-2">🏫</div>
                <h4 class="fw-bold">Total Institutions</h4>
                <p class="fs-4 text-primary">{{ \App\Models\User::where('role','institution')->count() }}</p>
            </div>
        </a>
    </div>

    {{-- ⭐ TOTAL PARTICIPANTS --}}
    <div class="col-md-4">
        <a href="{{ route('admin.details.participants') }}" class="text-decoration-none text-dark">
            <div class="card tm-card p-4 text-center shadow-sm">
                <div class="tm-icon mb-2">🧍</div>
                <h4 class="fw-bold">Total Participants</h4>
                <p class="fs-4 text-primary">{{ \App\Models\Student::count() }}</p>
            </div>
        </a>
    </div>

</div>


    {{-- ACTION CARD GRID --}}
    <h3 class="section-title mb-3">Actions</h3>
    <div class="row g-4">

        <div class="col-md-4">
            <a href="{{ route('admin.events.index') }}" class="text-decoration-none">
                <div class="card tm-card shadow-sm p-4">
                    <h5 class="fw-bold text-dark">🎭 Manage Events</h5>
                    <p class="text-muted">Create, edit & view event categories</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.registrations.index') }}" class="text-decoration-none">
                <div class="card tm-card shadow-sm p-4">
                    <h5 class="fw-bold text-dark">📝 Registrations</h5>
                    <p class="text-muted">View institution-wise registrations</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.results.index') }}" class="text-decoration-none">
                <div class="card tm-card shadow-sm p-4">
                    <h5 class="fw-bold text-dark">🏆 Results</h5>
                    <p class="text-muted">Calculate, publish & manage results</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.scoreboard', 'sharia') }}" class="text-decoration-none">
                <div class="card tm-card shadow-sm p-4">
                    <h5 class="fw-bold text-dark">📊 Scoreboard</h5>
                    <p class="text-muted">View institution score matrix</p>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="{{ route('admin.non_stage_scoreboard','sharia') }}" class="text-decoration-none">
                <div class="card tm-card shadow-sm p-4">
                    <h5 class="fw-bold text-dark">📝 Non-Stage Scoreboard</h5>
                    <p class="text-muted">View non-stage ranking table</p>
                </div>
            </a>
        </div>

    </div>

</div>

@endsection
