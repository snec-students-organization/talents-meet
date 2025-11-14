<x-app-layout>
    <div class="max-w-lg mx-auto py-10 text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Select Your Stage</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('stage_admin.setStage') }}">
            @csrf
            <div class="mb-6">
                <label for="stage_number" class="block text-gray-700 mb-2">Choose Stage Number</label>
                <select name="stage_number" id="stage_number" class="w-full border rounded p-3" required>
                    <option value="">Select Stage</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">Stage {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                Continue
            </button>
        </form>
    </div>
</x-app-layout>
