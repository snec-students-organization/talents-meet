<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Stage {{ $stage }} — Judge Dashboard</h1>

        @if($events->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-3 rounded">No events assigned to this stage.</div>
        @else
            <table class="min-w-full bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">Event Name</th>
                        <th class="p-3 text-left">Category</th>
                        <th class="p-3 text-left">Stream</th>
                        <th class="p-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $index => $event)
                        <tr class="border-b">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3">{{ $event->name }}</td>
                            <td class="p-3">{{ $event->category }}</td>
                            <td class="p-3 capitalize">{{ $event->stream }}</td>
                            <td class="p-3">
                                <a href="{{ route('judge.view_event', $event->id) }}"
                                   class="text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <a href="{{ route('judge.scores') }}"
   class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
   📄 View Score List
</a>

    </div>
</x-app-layout>
