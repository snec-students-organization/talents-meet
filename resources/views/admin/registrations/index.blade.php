@extends('layouts.app')

@section('content')
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
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.registrations.show', $event->id) }}" class="bg-white text-blue-600 px-3 py-1 rounded-md text-sm font-semibold hover:bg-blue-50 transition-colors">
                            View All Students
                        </a>
                        <!-- Stage Type Badge -->
                        <span class="{{ $event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-3 py-1 rounded-full text-sm font-semibold">
                            {{ $event->stage_type === 'stage' ? 'Stage Event' : 'Non-Stage Event' }}
                        </span>
                    </div>
                </div>

                <div class="p-4">
                    @if($event->registrations->isEmpty())
                        <p class="text-gray-500 italic">No students registered for this event yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="p-2 border text-xs">#</th>
                                        <th class="p-2 border text-xs">UID</th>
                                        <th class="p-2 border text-xs">Student Name</th>
                                        <th class="p-2 border text-xs">Institution</th>
                                        <th class="p-2 border text-xs">Category</th>
                                        <th class="p-2 border text-xs">Stage Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($event->registrations->take(5) as $index => $reg)
                                        <tr class="hover:bg-gray-50">
                                            <td class="p-2 border text-sm">{{ $index + 1 }}</td>
                                            <td class="p-2 border text-sm font-mono text-indigo-600">{{ $reg->student->uid ?? 'N/A' }}</td>
                                            <td class="p-2 border text-sm">{{ $reg->student->name }}</td>
                                            <td class="p-2 border text-sm">{{ $reg->institution->name ?? 'N/A' }}</td>
                                            <td class="p-2 border text-sm">{{ $event->category }}</td>
                                            <td class="p-2 border text-sm">
                                                <span class="{{ $event->stage_type === 'stage' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }} px-2 py-1 rounded-full text-xs">
                                                    {{ $event->stage_type === 'stage' ? 'Stage' : 'Non-Stage' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($event->registrations->count() > 5)
                                <div class="mt-2 text-center">
                                    <a href="{{ route('admin.registrations.show', $event->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        ... and {{ $event->registrations->count() - 5 }} more. View all
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
