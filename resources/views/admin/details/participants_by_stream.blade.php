@extends('layouts.app')

@section('content')

<h2 class="fw-bold text-primary mb-4">Participants Grouped by Stream & Institution</h2>

@foreach($institutions as $stream => $instList)

    <div class="card shadow mb-5">
        <div class="card-header text-white" style="background:#013A63;">
            <h5 class="mb-0">
                {{ ucwords(str_replace('_',' ', $stream)) }} Stream
            </h5>
        </div>

        <div class="card-body">

            @foreach($instList as $institution)

                <h5 class="fw-bold mb-2 mt-4">{{ $institution->name }}</h5>

                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>UID</th>
                            <th>Student Name</th>
                            <th>Events Participating</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($institution->students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->uid }}</td>
                                <td>{{ $student->name }}</td>
                                <td>
                                    @foreach($student->registrations as $reg)
                                        <span class="badge bg-primary">{{ $reg->event->name }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endforeach

        </div>
    </div>

@endforeach

@endsection
