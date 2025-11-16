<x-app-layout>
    <div class="max-w-6xl mx-auto p-6">

        <h1 class="text-3xl font-bold text-gray-800 mb-8">Admin Dashboard</h1>

        {{-- GRID MENU --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- EVENTS --}}
            <a href="{{ route('admin.events.index') }}"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-blue-700">🎭 Events</h2>
                <p class="text-gray-600 text-sm mt-2">
                    Add, edit, and manage all events.
                </p>
            </a>

            {{-- REGISTRATIONS --}}
            <a href="{{ route('admin.registrations.index') }}"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-green-700">📝 Registrations</h2>
                <p class="text-gray-600 text-sm mt-2">
                    View all student registrations.
                </p>
            </a>

            {{-- RESULTS --}}
            <a href="{{ route('admin.results.index') }}"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-purple-700">🏆 Results</h2>
                <p class="text-gray-600 text-sm mt-2">
                    Calculate & publish final results per stream.
                </p>
            </a>

            {{-- SCOREBOARD --}}
            <a href="{{ route('admin.scoreboard', 'sharia') }}"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-orange-700">📊 Stage Scoreboard</h2>
                <p class="text-gray-600 text-sm mt-2">
                    View top performers for stage events.
                </p>
            </a>

            {{-- NON-STAGE SCOREBOARD --}}
            <a href="{{ route('admin.non_stage_scoreboard', 'sharia') }}"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-red-700">📄 Non-Stage Scoreboard</h2>
                <p class="text-gray-600 text-sm mt-2">
                    View scores for non-stage events.
                </p>
            </a>

            {{-- PUBLIC RESULTS PAGE --}}
            <a href="/results/sharia"
               class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                <h2 class="text-xl font-semibold text-indigo-700">🌍 Public Results Page</h2>
                <p class="text-gray-600 text-sm mt-2">
                    View public leaderboard.
                </p>
            </a>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}" class="p-6 bg-white shadow rounded-lg hover:bg-blue-50 transition">
                @csrf
                <button type="submit" class="w-full text-left">
                    <h2 class="text-xl font-semibold text-gray-800">🚪 Logout</h2>
                    <p class="text-gray-600 text-sm mt-2">End session</p>
                </button>
            </form>

        </div>

    </div>
</x-app-layout>
