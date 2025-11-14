<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-green-700">Institution Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome, {{ Auth::user()->name }}!</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="font-semibold text-gray-700">👨‍🎓 My Participants</h3>
                <p class="text-sm text-gray-500 mt-1">View and manage participants from your institution.</p>
            </div>

            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="font-semibold text-gray-700">🏆 Results</h3>
                <p class="text-sm text-gray-500 mt-1">Track event performance and scores of your college.</p>
            </div>
        </div>
    </div>
</x-app-layout>
