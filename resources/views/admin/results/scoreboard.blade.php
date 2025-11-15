<x-app-layout>
    <div class="max-w-7xl mx-auto p-6">

        <h1 class="text-3xl font-bold mb-6">
            Scoreboard – {{ ucwords(str_replace('_',' ', $stream)) }} Stream
        </h1>

        <table class="min-w-full bg-white shadow text-sm">
            <thead class="bg-blue-100 font-bold">
                <tr>
                    <th class="p-3 w-48">Team</th>

                    {{-- Event columns --}}
                    @foreach($events as $event)
                        <th class="p-3 text-center">{{ $event->name }}</th>
                    @endforeach

                    {{-- TOTAL POINTS --}}
                    <th class="p-3 text-center bg-green-200">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($institutions as $inst)
                    @php
                        // Calculate total points
                        $totalPoints = 0;
                        foreach ($events as $event) {
                            $totalPoints += $matrix[$inst->institution_id][$event->id] ?? 0;
                        }
                    @endphp

                    <tr class="border-b">
                        <td class="p-3 font-semibold">{{ $inst->institution_name }}</td>

                        {{-- Event-wise points --}}
                        @foreach($events as $event)
                            <td class="p-3 text-center">
                                {{ $matrix[$inst->institution_id][$event->id] ?? 0 }}
                            </td>
                        @endforeach

                        {{-- TOTAL --}}
                        <td class="p-3 text-center font-bold bg-green-100">
                            {{ $totalPoints }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
