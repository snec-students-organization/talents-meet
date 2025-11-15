<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Non-Stage Events</h1>

        <table class="min-w-full bg-white shadow rounded">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">Event</th>
                    <th class="p-3">Stream</th>
                    <th class="p-3">Category</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-b">
                        <td class="p-3">{{ $event->name }}</td>
                        <td class="p-3 capitalize">{{ str_replace('_',' ',$event->stream) }}</td>
                        <td class="p-3">{{ $event->category }}</td>
                        <td class="p-3 text-center">
                            <a href="{{ route('judge.nonstage.event', $event->id) }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded">
                                Score Event
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
