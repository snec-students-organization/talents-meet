<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Talents Meet 2025</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #013A63 0%, #012A4A 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        .auth-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-logo img {
            height: 85px;
        }

        .auth-title {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            color: #F4A300;
            font-weight: 800;
            font-size: 26px;
            letter-spacing: 0.6px;
        }

        .auth-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            width: 100%;
        }

        @media (max-width: 480px) {
            .auth-logo img {
                height: 70px;
            }

            .auth-title {
                font-size: 22px;
            }

            .auth-card {
                padding: 20px;
            }
        }
    </style>

    @stack('styles')

</head>

<body>

    <div class="auth-wrapper">

        {{-- LOGO --}}
        <div class="auth-logo text-center mb-2">
            <img src="/logo.png" alt="Talents Meet Logo" class="img-fluid">
        </div>

        {{-- TITLE --}}
        <h2 class="auth-title">Talents Meet 2025</h2>

        {{-- AUTH CARD --}}
        <div class="auth-card">
            @yield('content')
        </div>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>
