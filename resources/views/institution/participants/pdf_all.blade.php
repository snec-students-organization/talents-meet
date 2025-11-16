<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $institution->name }} - All Participants</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 15px;
            background: #f3f3f3;
        }

        .title {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #1e3a8a;
            text-transform: uppercase;
        }

        .institution {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th {
            background: #1e40af;
            color: white;
            padding: 8px;
            font-weight: bold;
        }

        td {
            padding: 6px;
            background: #ffffff;
        }

        tr:nth-child(even) td {
            background: #f1f5f9;
        }

        .category {
            font-weight: bold;
            color: #d97706;
        }
    </style>
</head>

<body>

    <div class="title">Talents Meet 2025 - Participants List</div>
    <div class="institution">{{ $institution->name }}</div>

    <table border="1">
        <thead>
            <tr>
                <th>UID</th>
                <th>Name</th>
                <th>Event</th>
                <th>Category</th>
            </tr>
        </thead>

        <tbody>
            @foreach($participants as $p)
                <tr>
                    <td>{{ $p->student->uid }}</td>
                    <td>{{ $p->student->name }}</td>
                    <td>{{ $p->event->name }}</td>
                    <td class="category">{{ $p->event->category }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
