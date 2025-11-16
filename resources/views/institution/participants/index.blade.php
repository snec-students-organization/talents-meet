<x-app-layout>
<div class="max-w-5xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-6">👥 My Institution Participants</h1>

    <div class="mb-4 text-right">
        <a href="{{ route('institution.participants.downloadAll') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded shadow">
            ⬇ Download All Participants (PDF)
        </a>
    </div>

    <table class="min-w-full bg-white shadow rounded text-sm">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3">UID</th>
                <th class="p-3">Name</th>
                <th class="p-3">Events Count</th>
                <th class="p-3 text-center">Download</th>
            </tr>
        </thead>

        <tbody>
        @foreach($students as $student)
            <tr class="border-b hover:bg-gray-50">
                <td class="p-3">{{ $student->uid }}</td>
                <td class="p-3">{{ $student->name }}</td>
                <td class="p-3">
                    {{ $student->registrations->count() }}
                </td>
                <td class="p-3 text-center">
                    <a href="{{ route('institution.participants.downloadStudent', $student->id) }}"
                       class="bg-blue-600 text-white px-3 py-1 rounded text-sm">
                        ⬇ ID Card
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>

</div>
</x-app-layout>
