<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Stage {{ $stageNumber }} Dashboard
        </h1>

        <div class="mb-4 text-right">
            <a href="{{ route('stage_admin.select_stage') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                Change Stage
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($events->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
                No events assigned to this stage yet.
            </div>
        @else
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-700">
                        <th class="p-3">#</th>
                        <th class="p-3">Event Name</th>
                        <th class="p-3">Category</th>
                        <th class="p-3">Stream</th>
                        <th class="p-3">Type</th>
                        <th class="p-3">Level</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $index => $event)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold">{{ $event->name }}</td>
                            <td class="p-3">{{ $event->category }}</td>
                            <td class="p-3">{{ ucfirst(str_replace('_', ' ', $event->stream)) }}</td>
                            <td class="p-3">{{ ucfirst($event->type) }}</td>
                            <td class="p-3">{{ $event->level ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <a href="{{ route('stage_admin.events.view', $event->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                    View Participants
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</x-app-layout>
