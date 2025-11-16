<x-app-layout>
    <div class="max-w-5xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6">Non-Stage Events</h1>

        <table class="min-w-full bg-white shadow rounded text-sm">
            <thead class="bg-gray-100 font-semibold">
                <tr>
                    <th class="p-3">Event</th>
                    <th class="p-3">Category</th>
                    <th class="p-3">Type</th>
                    <th class="p-3">Stream</th>
                    <th class="p-3">Level</th>
                    <th class="p-3 text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($events as $event)
                    <tr class="border-b hover:bg-gray-50">

                        {{-- Event Name --}}
                        <td class="p-3 font-semibold">
                            {{ $event->name }}
                        </td>

                        {{-- Category --}}
                        <td class="p-3">
                            {{ $event->category }}
                        </td>

                        {{-- Type --}}
                        <td class="p-3 capitalize">
                            {{ $event->type }}
                        </td>

                        {{-- Stream --}}
                        <td class="p-3 capitalize">
                            {{ str_replace('_', ' ', $event->stream) }}
                        </td>

                        {{-- Level (Only for Sharia / SHE) --}}
                        <td class="p-3">
                            @if(in_array($event->stream, ['sharia','she']))
                                {{ $event->level ? ucwords(str_replace('_',' ', $event->level)) : '—' }}
                            @else
                                —
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="p-3 text-center">
                            <a href="{{ route('judge.nonstage.event', $event->id) }}"
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                🎧 Score Event
                            </a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
