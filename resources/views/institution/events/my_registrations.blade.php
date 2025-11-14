<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">My Registrations</h1>

        <table class="min-w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Event</th>
                    <th class="p-3">Student</th>
                    <th class="p-3">Stream</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Stage Type</th> <!-- Added column -->
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $reg)
                    <tr class="border-b">
                        <td class="p-3">{{ $reg->event->name }}</td>
                        <td class="p-3">{{ $reg->student->name }}</td>
                        <td class="p-3 capitalize">{{ str_replace('_', ' ', $reg->event->stream) }}</td>
                        <td class="p-3">{{ $reg->event->category }}</td>

                        <!-- Stage Type -->
                        <td class="p-3">
                            <span class="{{ $reg->event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-sm">
                                {{ $reg->event->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">No registrations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
