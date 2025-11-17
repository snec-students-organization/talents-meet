<table class="table table-bordered tm-table mb-4">
    <thead>
        <tr>
            <th width="35%">Event Name</th>
            <th width="10%">Cat.</th>
            <th width="20%">Type</th>
            <th width="20%">Level</th>
        </tr>
    </thead>
    <tbody>
        @if($items->isEmpty())
            <tr>
                <td colspan="4" class="text-center text-muted py-2">
                    No events found.
                </td>
            </tr>
        @else
            @foreach($items as $event)
                <tr>
                    <td>{{ $event->name }}</td>
                    <td>{{ $event->category }}</td>
                    <td>{{ ucfirst($event->type) }}</td>
                    <td>
                        {{ $event->level ? ucwords(str_replace('_',' ', $event->level)) : '-' }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
