@extends('layouts.app')

@section('content')

<h2 class="fw-bold text-primary mb-4">Institutions Grouped by Stream</h2>

@foreach($institutions as $stream => $list)
    <div class="card shadow mb-4">
        <div class="card-header text-white" style="background:#013A63;">
            <h5 class="mb-0">
                {{ ucwords(str_replace('_',' ', $stream)) }} Stream
            </h5>
        </div>

        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Institution Name</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $index => $inst)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $inst->name }}</td>
                            <td>{{ $inst->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach

@endsection
