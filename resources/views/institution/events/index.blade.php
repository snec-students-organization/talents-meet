<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Available Events</h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-3">Event Name</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Stream</th>
                    <th class="p-3">Stage Type</th> <!-- Added column -->
                    <th class="p-3">Registered Students</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $event->name }}</td>
                        <td class="p-3">{{ $event->category }}</td>
                        <td class="p-3 capitalize">{{ $event->type }}</td>
                        <td class="p-3 capitalize">{{ str_replace('_', ' ', $event->stream) }}</td>

                        <!-- Stage Type -->
                        <td class="p-3">
                            <span class="{{ $event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-sm">
                                {{ $event->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}
                            </span>
                        </td>

                        <!-- Registered Students -->
                        <td class="p-3">
                            @if($event->registrations->isEmpty())
                                <span class="text-gray-400 italic">None</span>
                            @else
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($event->registrations as $registration)
                                        <li>
                                            <span class="font-semibold">{{ $registration->student->uid }}</span>
                                            — {{ $registration->student->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>

                        <!-- Register Button -->
                        <td class="p-3 text-center">
                            <a href="{{ route('institution.events.registerForm', $event->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
                                Register Student
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
