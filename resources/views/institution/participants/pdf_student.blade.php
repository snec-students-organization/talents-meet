<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ID Card - {{ $student->name }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f3f3;
        }

        .card {
            width: 100%;
            height: 100%;
            padding: 18px;
            box-sizing: border-box;
            background: white;
            border: 3px solid #2563eb;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 8px;
            font-size: 18px;
            text-transform: uppercase;
        }

        .institution {
            text-align: center;
            font-size: 13px;
            color: #444;
            margin-bottom: 12px;
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid #2563eb;
            padding: 8px;
            margin-bottom: 12px;
            border-radius: 4px;
        }

        .info-box p {
            margin: 3px 0;
            font-size: 13px;
        }

        .events-title {
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 5px;
            margin-top: 5px;
            font-size: 14px;
        }

        .events {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 8px;
            border-radius: 4px;
        }

        .events ul {
            margin: 0;
            padding-left: 18px;
            font-size: 12px;
        }

        .qr {
            text-align: center;
            margin-top: 12px;
        }

        .qr img {
            width: 110px;
            height: 110px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 5px;
            color: #555;
            font-style: italic;
        }
    </style>
</head>

<body>

<div class="card">

    {{-- FESTIVAL NAME --}}
    <div class="header">
        TALENTS MEET 2025 — PARTICIPANT ID CARD
    </div>

    {{-- INSTITUTION NAME --}}
    <div class="institution">
        {{ $institution->name }}
    </div>

    {{-- STUDENT INFO --}}
    <div class="info-box">
        <p><strong>Name:</strong> {{ $student->name }}</p>
        <p><strong>UID:</strong> {{ $student->uid }}</p>
        <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
    </div>

    {{-- EVENTS --}}
    <div class="events-title">Registered Events</div>

    <div class="events">
        <ul>
            @foreach($events as $reg)
                <li>
                    {{ $reg->event->name }} —
                    <strong>{{ strtoupper($reg->event->category) }}</strong>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- QR CODE --}}
    <div class="qr">
        <img src="data:image/svg+xml;base64,{{ $qr }}">
    </div>

    <div class="footer">
        Scan QR to verify participant • {{ now()->format('Y') }}
    </div>

</div>

</body>
</html>
