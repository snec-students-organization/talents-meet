<x-stage-admin-layout>
    <div class="max-w-7xl mx-auto p-6">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Stage {{ $stageNumber }} Dashboard</h1>
                <p class="text-gray-500 mt-1">Manage events and verify participants for your stage.</p>
            </div>
            
            <a href="{{ route('stage_admin.select_stage') }}" 
               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                <span>🔄</span> Switch Stage
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-700 p-4 rounded-lg border border-emerald-200 mb-6 flex items-center gap-2">
                ✅ {{ session('success') }}
            </div>
        @endif

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wide">Total Events</div>
                <div class="mt-2 text-3xl font-bold text-gray-900">{{ $events->count() }}</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wide">Completed</div>
                <div class="mt-2 text-3xl font-bold text-emerald-600">{{ $completedCount }}</div>
                <div class="mt-1 text-xs text-gray-500">Chest numbers assigned</div>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="text-gray-500 text-sm font-medium uppercase tracking-wide">Pending</div>
                <div class="mt-2 text-3xl font-bold text-orange-600">{{ $pendingCount }}</div>
                <div class="mt-1 text-xs text-gray-500">Awaiting chest numbers</div>
            </div>
        </div>

        @if($events->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-8 rounded-xl text-center">
                <div class="text-4xl mb-3">⚠️</div>
                <h3 class="text-lg font-bold">No Events Assigned</h3>
                <p class="text-sm mt-2">There are currently no events assigned to Stage {{ $stageNumber }}.</p>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-700">Assigned Events</h2>
                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $events->count() }} Events</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stream</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($events as $index => $event)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-bold text-gray-900">{{ $event->name }}</div>
                                            @if($event->is_completed)
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    ✓ Complete
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $event->category }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">
                                        {{ str_replace('_', ' ', $event->stream) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">
                                        <div><span class="font-medium">Type:</span> {{ ucfirst($event->type) }}</div>
                                        @if($event->level)
                                            <div class="mt-1 text-indigo-600 font-medium">Level: {{ $event->level }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('stage_admin.events.view', $event->id) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-indigo-600 text-xs font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            <span>👁️</span> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-stage-admin-layout>
