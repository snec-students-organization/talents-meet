<x-institution-layout>
    <div class="max-w-7xl mx-auto p-6">
        
        {{-- HEADER & TABS --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Available Events</h1>
            
            <div class="flex p-1 space-x-1 bg-gray-100 rounded-xl">
                @foreach(['individual' => 'Individual', 'group' => 'Group', 'general' => 'General', 'off_stage' => 'Off-Stage'] as $key => $label)
                    <a href="{{ route('institution.events.index', ['type' => $key]) }}"
                       class="px-4 py-2 text-sm font-medium rounded-lg transition-all
                              {{ $type === $key ? 'bg-white text-indigo-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200 mb-6 flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- EVENTS TABLE --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            @if($events->isEmpty())
                <div class="p-12 text-center">
                    <div class="text-5xl mb-4">📭</div>
                    <h3 class="text-lg font-medium text-gray-900">No events found</h3>
                    <p class="text-gray-500 mt-1">There are no {{ str_replace('_', ' ', $type) }} events available at the moment.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stream</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($events as $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $event->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $event->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ $event->type }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ str_replace('_', ' ', $event->stream) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($event->registrations->isEmpty())
                                            <span class="text-gray-400 text-xs italic">No registrations</span>
                                        @else
                                            <div class="flex -space-x-2 overflow-hidden">
                                                @foreach($event->registrations->take(3) as $reg)
                                                    <div class="inline-block h-6 w-6 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs ring-2 ring-white" title="{{ $reg->student->name }}">
                                                        {{ substr($reg->student->name, 0, 1) }}
                                                    </div>
                                                @endforeach
                                                @if($event->registrations->count() > 3)
                                                    <div class="inline-block h-6 w-6 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center text-xs ring-2 ring-white">
                                                        +{{ $event->registrations->count() - 3 }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('institution.events.registerForm', $event->id) }}" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <span>➕</span> Register
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-institution-layout>
