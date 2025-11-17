@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #E6F0FF !important;
    }

    .hero-title {
        color: #012A4A;
        font-weight: 900;
        letter-spacing: 1px;
        font-size: 2.4rem;
        text-transform: uppercase;
    }

    .hero-highlight {
        color: #F4A300;
    }

    .logo-img {
        height: 130px;
        border-radius: 8px;
    }

    .card-custom {
        border-radius: 14px;
        border: none;
        transition: 0.3s ease;
        overflow: hidden;
    }

    .card-custom:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    }

    /* STREAM BUTTON STYLE */
    .stream-btn {
        background: white;
        color: #012A4A;
        border: 2px solid #013A63;
        font-weight: 700;
        padding: 10px;
        transition: .25s;
        border-radius: 8px;
    }

    .stream-btn:hover {
        background: #013A63;
        color: #ffffff;
        transform: scale(1.03);
    }

    /* LOGIN BUTTONS */
    .login-btn {
        font-weight: 600;
        border-radius: 8px;
        letter-spacing: .5px;
        padding: 10px 24px;
    }

    .login-inst { background: #013A63; border-color: #013A63; }
    .login-inst:hover { background:#012A4A; }

    .login-judge { background: #2ECC71; border-color:#27ae60; }
    .login-judge:hover { background:#27ae60; }

    .login-stage { background: #34495E; border-color:#2C3E50; }
    .login-stage:hover { background:#2C3E50; }

    .login-admin { background: #C0392B; border-color:#922B21; }
    .login-admin:hover { background:#922B21; }

    .header-dark {
        background: #013A63;
        color: white;
    }

    .header-yellow {
        background: #F4A300;
        color: #012A4A;
        font-weight: 700;
    }

    /* STATS CARDS */
    .stats-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        text-align: center;
        transition: .3s ease;
    }

    .stats-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .stats-icon {
        font-size: 38px;
        color: #013A63;
        margin-bottom: 10px;
    }

    .stats-number {
        font-weight: 900;
        color: #013A63;
        font-size: 32px;
    }

    .stats-label {
        font-size: 15px;
        color: #012A4A;
        font-weight: 600;
    }
</style>


<div class="text-center my-5">

    {{-- TITLE --}}
    <h1 class="hero-title mb-3">
        Talents Meet <span class="hero-highlight">2025</span>  
        <div style="font-size:18px; font-weight:600; margin-top:5px;">Second Edition</div>
    </h1>

    {{-- LOGO --}}
    <img src="/logo.png" class="logo-img mb-4">

    {{-- SUBTEXT --}}
    <p class="lead" style="max-width: 680px; margin:auto; color:#013A63;">
        Kerala’s Premier Multi-Stream Arts & Talent Festival – A Showcase of Excellence, Creativity, and Culture.
    </p>
</div>


<div class="container">

    {{-- 🔥 NEW STATS SECTION --}}
    <div class="col-md-10 mx-auto mb-5">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="stats-card shadow-sm">
                    <div class="stats-icon">🎭</div>
                    <div class="stats-number">{{ \App\Models\Event::count() }}</div>
                    <div class="stats-label">Total Events</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card shadow-sm">
                    <div class="stats-icon">🏫</div>
                    <div class="stats-number">{{ \App\Models\User::where('role','institution')->count() }}</div>
                    <div class="stats-label">Total Institutions</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card shadow-sm">
                    <div class="stats-icon">🧍</div>
                    <div class="stats-number">{{ \App\Models\Student::count() }}</div>
                    <div class="stats-label">Total Participants</div>
                </div>
            </div>

        </div>
    </div>


    {{-- 🔵 RESULTS SECTION --}}
    <div class="col-md-10 mx-auto mb-4">
        <div class="card shadow card-custom">
            <div class="card-header header-dark text-center">
                <h5 class="mb-0">View Stream Results</h5>
            </div>

            <div class="card-body p-4">
                <div class="row g-3">
                    @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'] as $s)
                        <div class="col-md-4 col-lg-4">
                            <a href="/results/{{ $s }}" class="btn stream-btn w-100">
                                {{ ucwords(str_replace('_',' ', $s)) }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>


    {{-- 🟡 LOGIN SECTION --}}
    <div class="col-md-10 mx-auto mb-5">
        <div class="card shadow card-custom">
            <div class="card-header header-yellow text-center">
                <h5 class="mb-0">Login Sections</h5>
            </div>

            <div class="card-body d-flex gap-3 flex-wrap justify-content-center py-4">

                <a href="/login" class="btn login-btn login-inst text-white">Institution Login</a>
                <a href="/login" class="btn login-btn login-judge text-white">Judge Login</a>
                <a href="/login" class="btn login-btn login-stage text-white">Stage Admin Login</a>
                <a href="/login" class="btn login-btn login-admin text-white">Admin Login</a>

            </div>
        </div>
    </div>

</div>

@endsection
