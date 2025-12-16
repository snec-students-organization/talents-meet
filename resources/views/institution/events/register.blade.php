<x-institution-layout>
    <div class="max-w-2xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">
            Register Student for {{ $event->name }}
        </h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
        @endif

        <form action="{{ route('institution.events.register', $event->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Student UID</label>
                <input type="text" name="uid" class="w-full border rounded p-2" placeholder="Enter Student UID" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Student Name</label>
                <input type="text" name="name" class="w-full border rounded p-2" placeholder="Enter Student Name" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Gender (optional)</label>
                <select name="gender" class="w-full border rounded p-2">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded">
                Register
            </button>
        </form>
    </div>
</x-institution-layout>
