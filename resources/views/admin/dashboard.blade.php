<x-app-layout>
    <div class="p-6">
        <h1 class="text-3xl font-bold text-blue-700">Admin Dashboard</h1>
        <p class="mt-2 text-gray-600">Welcome, {{ Auth::user()->name }}!</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="font-semibold text-gray-700">🎭 Manage Events</h3>
                <p class="text-sm text-gray-500 mt-1">Create, edit, or remove fest events.</p>
            </div>

            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="font-semibold text-gray-700">👨‍🎓 Manage Students</h3>
                <p class="text-sm text-gray-500 mt-1">Add or assign student logins.</p>
            </div>

            <div class="bg-white shadow rounded-xl p-4">
                <h3 class="font-semibold text-gray-700">🏆 Leaderboard</h3>
                <p class="text-sm text-gray-500 mt-1">Monitor points and overall rankings.</p>
            </div>
        </div>
    </div>
</x-app-layout>
