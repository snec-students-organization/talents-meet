<x-app-layout>
    <div class="max-w-md mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Select Your Stage</h1>

        <form method="POST" action="{{ route('judge.set_stage') }}">
            @csrf
            <label class="block mb-2 text-gray-700">Stage Number:</label>
            <select name="stage_number" class="border rounded w-full p-2 mb-4" required>
                <option value="">Select Stage</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">Stage {{ $i }}</option>
                @endfor
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Continue</button>
        </form>
    </div>
</x-app-layout>
