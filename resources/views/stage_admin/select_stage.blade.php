<x-stage-admin-layout>
    <div class="max-w-7xl mx-auto p-6 flex flex-col items-center justify-center min-h-[calc(100vh-100px)]">
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 w-full max-w-md text-center">
            <div class="text-5xl mb-4">🎤</div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Select Your Stage</h1>
            <p class="text-gray-500 mb-8 text-sm">Please choose the stage number you are currently managing.</p>

            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-6 border border-red-100 flex items-center justify-center gap-2">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('stage_admin.setStage') }}">
                @csrf
                <div class="mb-6 text-left">
                    <label for="stage_number" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Stage Number</label>
                    <select name="stage_number" id="stage_number" 
                            class="w-full border-gray-300 rounded-lg p-3 text-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-all" required>
                        <option value="">Choose Stage...</option>
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">Stage {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                    Continue to Dashboard &rarr;
                </button>
            </form>
        </div>
        
        <div class="mt-8 text-center text-sm text-gray-400">
            &copy; 2025 Talents Meet. Stage Administration.
        </div>
    </div>
</x-stage-admin-layout>
