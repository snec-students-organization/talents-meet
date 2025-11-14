<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Event Registrations Overview</h1>

        @foreach($events as $event)
            <div class="bg-white shadow-md rounded-xl mb-8">
                <div class="bg-blue-600 text-white p-4 rounded-t-xl flex justify-between items-center">
                    <h2 class="text-xl font-semibold">
                        {{ $event->name }} 
                        <span class="text-sm font-normal">
                            ({{ strtoupper($event->category) }} | {{ ucfirst($event->type) }} | {{ ucfirst(str_replace('_', ' ', $event->stream)) }})
                        </span>
                    </h2>
                    <!-- Stage Type Badge -->
                    <span class="{{ $event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $event->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}
                    </span>
                </div>

                <div class="p-4">
                    @if($event->registrations->isEmpty())
                        <p class="text-gray-500 italic">No students registered for this event yet.</p>
                    @else
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-left">
                                    <th class="p-2 border">#</th>
                                    <th class="p-2 border">Student Name</th>
                                    <th class="p-2 border">Institution</th>
                                    <th class="p-2 border">Stream</th>
                                    <th class="p-2 border">Category</th>
                                    <th class="p-2 border">Stage Type</th> <!-- Added column -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($event->registrations as $index => $reg)
                                    <tr class="hover:bg-gray-50">
                                        <td class="p-2 border">{{ $index + 1 }}</td>
                                        <td class="p-2 border">{{ $reg->student->name }}</td>
                                        <td class="p-2 border">{{ $reg->institution->name ?? 'N/A' }}</td>
                                        <td class="p-2 border capitalize">{{ str_replace('_', ' ', $event->stream) }}</td>
                                        <td class="p-2 border">{{ $event->category }}</td>
                                        <td class="p-2 border">
                                            <span class="{{ $event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-xs">
                                                {{ $event->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
