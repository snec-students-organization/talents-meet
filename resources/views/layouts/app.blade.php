<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Talents Meet 2025</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CSS --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #E6F0FF;
            font-family: "Poppins", sans-serif;
        }

        /* NAVBAR */
        .navbar-custom {
            background: linear-gradient(90deg, #012A4A, #014F86);
            padding: 12px 20px;
        }

        .brand-title {
            font-size: 22px;
            font-weight: 800;
            color: #F4A300;
            letter-spacing: 1px;
        }

        /* SIDEBAR */
        .sidebar {
            background: #012A4A;
            min-height: 100vh;
            padding-top: 15px;
            border-right: 3px solid #013A63;
        }

        .sidebar a {
            color: #dbeafe;
            padding: 14px 20px;
            margin: 4px 0;
            display: block;
            font-size: 15px;
            font-weight: 500;
            border-radius: 6px;
            transition: 0.25s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #013A63;
            color: #ffffff;
            transform: translateX(5px);
        }

        /* MAIN CONTENT */
        .main-area {
            padding: 30px 25px;
        }

        .dropdown-menu a:hover {
            background-color: #f4f4f4;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-dark navbar-custom shadow-sm">
        <div class="container-fluid d-flex justify-content-between align-items-center">

            <span class="brand-title">🌟 Talents Meet 2025</span>

            <div>
                @auth
                    <div class="dropdown">
                        <button class="btn btn-warning dropdown-toggle fw-semibold px-3"
                            data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a>
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger fw-bold">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>


    {{-- CONTENT AREA --}}
    <div class="container-fluid px-0">
        <div class="row g-0">

            {{-- SIDEBAR --}}
            @auth
                <div class="col-md-2 sidebar">

                    {{-- ADMIN --}}
                    @if(Auth::user()->role == 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            🏠 Dashboard
                        </a>
                        <a href="{{ route('admin.events.index') }}"
                           class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                            🎭 Events
                        </a>
                        <a href="{{ route('admin.registrations.index') }}"
                           class="{{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                            📝 Registrations
                        </a>
                        <a href="{{ route('admin.results.index') }}"
                           class="{{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                            🏆 Results
                        </a>
                    @endif

                    {{-- JUDGE --}}
                    @if(Auth::user()->role == 'judge')
                        <a href="{{ route('judge.dashboard') }}"
                           class="{{ request()->routeIs('judge.dashboard') ? 'active' : '' }}">
                            🎤 Judge Dashboard
                        </a>

                        <a href="{{ route('judge.scores') }}"
                           class="{{ request()->routeIs('judge.scores') ? 'active' : '' }}">
                            📊 Score List
                        </a>

                        <a href="{{ route('judge.nonstage') }}"
                           class="{{ request()->routeIs('judge.nonstage') ? 'active' : '' }}">
                            📝 Non-Stage
                        </a>
                    @endif

                    {{-- INSTITUTION --}}
                    @if(Auth::user()->role == 'institution')
                        <a href="{{ route('institution.dashboard') }}"
                           class="{{ request()->routeIs('institution.dashboard') ? 'active' : '' }}">
                            🏫 Dashboard
                        </a>

                        <a href="{{ route('institution.events.index') }}"
                           class="{{ request()->routeIs('institution.events.*') ? 'active' : '' }}">
                            📅 Events
                        </a>

                        <a href="{{ route('institution.participants') }}"
                           class="{{ request()->routeIs('institution.participants') ? 'active' : '' }}">
                            🆔 Participants
                        </a>
                    @endif

                    {{-- STAGE ADMIN --}}
                    @if(Auth::user()->role == 'stage_admin')
                        <a href="{{ route('stage_admin.dashboard') }}"
                           class="{{ request()->routeIs('stage_admin.dashboard') ? 'active' : '' }}">
                            🎭 Stage Dashboard
                        </a>
                    @endif

                </div>
            @endauth

            {{-- MAIN PAGE --}}
            <div class="{{ Auth::check() ? 'col-md-10' : 'col-md-12' }} main-area">
                @yield('content')
            </div>

        </div>
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
