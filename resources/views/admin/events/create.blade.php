<x-app-layout>
    <div class="max-w-3xl mx-auto py-10">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Add New Event</h1>

        <form action="{{ route('admin.events.store') }}" method="POST"
              x-data="{ 
                  type: '', 
                  stream: '', 
                  showLevel() {
                      // ✅ Show level only if type ≠ general AND stream = sharia or she
                      return this.type !== 'general' && ['sharia', 'she'].includes(this.stream);
                  } 
              }">
            @csrf

            <!-- Event Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Event Name</label>
                <input type="text" name="name" class="w-full border rounded p-2 mt-1" required>
            </div>

            <!-- Category -->
            <div class="mb-4">
                <label class="block text-gray-700">Category</label>
                <select name="category" class="w-full border rounded p-2 mt-1" required>
                    <option value="">Select Category</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            
            <!-- Stage Type -->
<div class="mb-4">
    <label class="block text-gray-700">Stage Type</label>
    <select name="stage_type" class="w-full border rounded p-2 mt-1" required>
        <option value="">Select Type</option>
        <option value="stage">Stage Event</option>
        <option value="non_stage">Non-Stage Event</option>
    </select>
</div>

            <!-- Type -->
            <div class="mb-4">
                <label class="block text-gray-700">Event Type</label>
                <select name="type" x-model="type" class="w-full border rounded p-2 mt-1" required>
                    <option value="">Select Type</option>
                    <option value="individual">Individual</option>
                    <option value="group">Group</option>
                    <option value="general">General</option>
                </select>
            </div>

            <!-- Stream -->
            <div class="mb-4">
                <label class="block text-gray-700">Stream</label>
                <select name="stream" x-model="stream" class="w-full border rounded p-2 mt-1" required>
                    <option value="">Select Stream</option>
                    <option value="sharia">Sharia</option>
                    <option value="sharia_plus">Sharia Plus</option>
                    <option value="she">SHE</option>
                    <option value="she_plus">SHE Plus</option>
                    <option value="life">Life</option>
                    <option value="life_plus">Life Plus</option>
                    <option value="bayyinath">Bayyinath</option>
                </select>
            </div>

            <!-- Level (only if type ≠ general and stream = sharia or she) -->
            <div class="mb-4" x-show="showLevel()">
                <label class="block text-gray-700">Level</label>
                <select name="level" class="w-full border rounded p-2 mt-1">
                    <option value="">Select Level</option>
                    <option value="sanaviyya_ulya">Sanaviyya Ulya</option>
                    <option value="bakalooriyya">Bakalooriyya</option>
                    <option value="majestar">Majestar</option>
                </select>
            </div>

            <hr class="my-6 border-gray-300">

            <!-- 🧩 Rules Section -->
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Event Rules & Restrictions</h2>

            <!-- Allowed Streams -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Allowed Streams (Optional)</label>
                <p class="text-sm text-gray-500 mb-2">Select which streams can participate in this event</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['sharia', 'sharia_plus', 'she', 'she_plus', 'life', 'life_plus', 'bayyinath'] as $streamOption)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="allowed_streams[]" value="{{ $streamOption }}">
                            <span class="capitalize">{{ str_replace('_', ' ', $streamOption) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Max Participants (only for group events) -->
            <div class="mb-4" x-show="type === 'group'">
                <label class="block text-gray-700 mb-1">Maximum Participants (for group event)</label>
                <input type="number" name="max_participants" min="1" max="10" class="w-full border rounded p-2 mt-1">
            </div>

            <!-- Max Institution Entries -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-1">Max Entries per Institution</label>
                <input type="number" name="max_institution_entries" min="1" value="1" class="w-full border rounded p-2 mt-1">
            </div>

            <hr class="my-6 border-gray-300">

            <!-- Submit -->
            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md">
                    Add Event
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
