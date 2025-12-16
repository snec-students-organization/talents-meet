<x-institution-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Register Student for {{ $event->name }}
        </h1>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-sm text-blue-800">
            <h3 class="font-bold mb-2">📋 Registration Limits</h3>
            <ul class="list-disc list-inside space-y-1">
                <li>Allowed Entries per Institution: <strong>{{ $event->max_institution_entries ?? 1 }}</strong></li>
                @if(($event->max_participants ?? 1) > 1)
                    <li>Max Participants per Team: <strong>{{ $event->max_participants }}</strong></li>
                @endif
                @if($event->registrations()->where('institution_id', auth()->id())->count() >= ($event->max_institution_entries ?? 1))
                    <li class="text-red-600 font-bold mt-1">⚠️ Limit Reached! You cannot register more entries.</li>
                @else
                    <li class="text-green-600 mt-1">
                        Entries Used: <strong>{{ $event->registrations()->where('institution_id', auth()->id())->count() }}</strong> / {{ $event->max_institution_entries ?? 1 }}
                    </li>
                @endif
            </ul>
        </div>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('institution.events.register', $event->id) }}" method="POST">
            @csrf

        <form action="{{ route('institution.events.register', $event->id) }}" method="POST">
            @csrf

            @php
                // Calculate Total Capacity (Total Slots * Participants per Slot)
                // If entries=2, team=5, then we allow 10 specific student inputs.
                $maxEntries = $event->max_institution_entries ?? 1;
                $perEntry = $event->max_participants ?? 1;
                $totalCapacity = $maxEntries * $perEntry;
                
                // Existing data count
                $existingCount = $existingRegistrations->count();
            @endphp

            <div class="space-y-4">
                @for($i = 0; $i < $totalCapacity; $i++)
                    @php
                        // Get existing data if available
                        $reg = $existingRegistrations[$i] ?? null;
                        $student = $reg ? $reg->student : null;
                    @endphp

                    <div class="bg-white p-4 rounded-lg shadow-sm border {{ $student ? 'border-green-200' : 'border-gray-100' }} relative">
                        <div class="absolute top-2 right-2 text-xs text-gray-400 font-mono">
                            #{{ $i + 1 }}
                            @if($student) <span class="text-green-600 font-bold ml-1">✓ Registered</span> @endif
                        </div>
                        
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Participant {{ $i + 1 }}</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">UID</label>
                                <input type="text" 
                                       name="participants[{{ $i }}][uid]" 
                                       value="{{ $student->uid ?? '' }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                       placeholder="Student UID">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Name</label>
                                <input type="text" 
                                       name="participants[{{ $i }}][name]" 
                                       value="{{ $student->name ?? '' }}"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm" 
                                       placeholder="Student Name">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Gender</label>
                                <select name="participants[{{ $i }}][gender]" 
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                    <option value="">Select</option>
                                    <option value="male" {{ ($student->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ ($student->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-colors flex justify-center items-center gap-2">
                    <span>💾</span> {{ $existingCount > 0 ? 'Update Registration' : 'Register Participants' }}
                </button>
                <p class="text-center text-xs text-gray-500 mt-2">
                    * Clearing a row's data and saving will remove that participant.
                </p>
            </div>
        </form>
    </div>
</x-institution-layout>
