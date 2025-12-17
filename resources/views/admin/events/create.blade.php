@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="eventForm()">
    
    {{-- HEADER --}}
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Create New Event</h1>
            <p class="text-slate-500 text-sm mt-1">Configure event details and registration rules.</p>
        </div>
        <a href="{{ route('admin.events.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to List
        </a>
    </div>

    <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- MAIN INFO CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-100 text-indigo-600 rounded-md text-sm">📝</span>
                    Event Details
                </h2>
            </div>
            
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- EVENT NAME --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Event Name</label>
                    <input type="text" name="name" 
                           class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition-all" 
                           placeholder="e.g. Quran Recitation" required>
                </div>

                {{-- CATEGORY --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Category</label>
                    <select name="category" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Category</option>
                        @foreach(['A','B','C','D'] as $c)
                            <option value="{{ $c }}">Category {{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- STAGE TYPE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Stage Type</label>
                    <select name="stage_type" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Type</option>
                        <option value="stage">🎭 Stage Event</option>
                        <option value="non_stage">📝 Non-Stage Event</option>
                    </select>
                </div>

                {{-- EVENT TYPE --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Participation Type</label>
                    <select name="type" x-model="type" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Type</option>
                        <option value="individual">👤 Individual</option>
                        <option value="group">👥 Group</option>
                        <option value="general">🌐 General</option>
                    </select>
                </div>

                {{-- CREATION MODE (Single vs Multi) --}}
                <div class="md:col-span-2 bg-slate-50 p-4 rounded-lg border border-slate-200 mb-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Creation Mode</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="mode" value="single" x-model="mode" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700">Single Stream</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="mode" value="multi" x-model="mode" class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-slate-700 font-medium">Multi-Stream (Batch Create)</span>
                        </label>
                    </div>
                    <p class="text-xs text-slate-500 mt-2" x-show="mode === 'multi'">Select multiple streams to create separate events for each one instantly.</p>
                </div>

                {{-- STREAM (Single Mode) --}}
                <div x-show="mode === 'single'">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Stream</label>
                    <select name="stream" x-model="stream" 
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
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

                {{-- STREAMS (Multi Mode) --}}
                <div x-show="mode === 'multi'" class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Target Streams</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath'] as $s)
                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer transition-colors"
                               :class="selectedStreams.includes('{{ $s }}') ? 'bg-indigo-50 border-indigo-200' : ''">
                            <input type="checkbox" name="streams[]" value="{{ $s }}" x-model="selectedStreams"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-slate-600 capitalize font-medium">
                                {{ str_replace('_',' ', $s) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- SINGLE MODE LEVEL --}}
                <div x-show="mode === 'single' && showLevel" x-transition class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Level (Optional)</label>
                    <select name="level"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Level</option>
                        <option value="Sanaviyya Ulya">Sanaviyya Ulya</option>
                        <option value="Bakalooriyya">Bakalooriyya</option>
                        <option value="Majestar">Majestar</option>
                    </select>
                </div>

                {{-- MULTI MODE: SHARIA LEVEL --}}
                <div x-show="showShariaLevel" x-transition class="md:col-span-2 bg-indigo-50 p-3 rounded-lg border border-indigo-100">
                    <label class="block text-sm font-medium text-indigo-700 mb-1">Level for Sharia</label>
                    <select name="sharia_level"
                            class="w-full rounded-lg border-indigo-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Sharia Level</option>
                        <option value="Sanaviyya Ulya">Sanaviyya Ulya</option>
                        <option value="Bakalooriyya">Bakalooriyya</option>
                        <option value="Majestar">Majestar</option>
                    </select>
                </div>

                {{-- MULTI MODE: SHE LEVEL --}}
                <div x-show="showSheLevel" x-transition class="md:col-span-2 bg-pink-50 p-3 rounded-lg border border-pink-100">
                    <label class="block text-sm font-medium text-pink-700 mb-1">Level for SHE</label>
                    <select name="she_level"
                            class="w-full rounded-lg border-pink-300 focus:border-pink-500 focus:ring-pink-500 shadow-sm">
                        <option value="">Select SHE Level</option>
                        <option value="Sanaviyya Ulya">Sanaviyya Ulya</option>
                        <option value="Bakalooriyya">Bakalooriyya</option>
                        <option value="Majestar">Majestar</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- RULES CARD --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-slate-50">
                <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <span class="p-1.5 bg-amber-100 text-amber-600 rounded-md text-sm">⚖️</span>
                    Rules & Restrictions
                </h2>
            </div>

            <div class="p-6 space-y-6">
                
                {{-- ALLOWED STREAMS --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-3">Allowed Streams (Optional)</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath'] as $s)
                        <label class="relative flex items-center p-3 rounded-lg border border-gray-200 hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="allowed_streams[]" value="{{ $s }}"
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-slate-600 capitalize font-medium">
                                {{ str_replace('_',' ', $s) }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                    <p class="text-xs text-slate-400 mt-2">Leave empty to allow all streams.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- MAX PARTICIPANTS --}}
                    <div x-show="type === 'group'" x-transition>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Max Participants per Team</label>
                        <input type="number" name="max_participants" min="1" max="50"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>

                    {{-- MAX ENTRIES --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Max Entries per Institution</label>
                        <input type="number" name="max_institution_entries" min="1" value="1"
                               class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM ACTIONS --}}
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.events.index') }}" 
               class="px-5 py-2.5 bg-white border border-gray-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-100 transition-all shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Create Event
            </button>
        </div>
    </form>
</div>

<script>
    function eventForm() {
        return {
            mode: 'single', // single or multi
            type: '',
            stream: '',
            selectedStreams: [],
            
            get showLevel() {
                if (this.type === 'general') return false;
                // Only for single mode
                if (this.mode !== 'single') return false;
                
                // Only "sharia" and "she" and maybe "life_plus" (check requirement) need levels.
                // User explicitly said: "dont ask the level for she plus and sharia plus"
                // Assuming "life_plus" still needs it based on original seeder?
                // Let's stick to what's requested: remove internal plus ones.
                // Seeder said: life_plus has levels.
                const levelsStreams = ['sharia', 'she', 'life_plus'];
                return levelsStreams.includes(this.stream);
            },
            get showShariaLevel() {
                if (this.type === 'general' || this.mode !== 'multi') return false;
                // Only 'sharia', not 'sharia_plus'
                return this.selectedStreams.includes('sharia');
            },
            get showSheLevel() {
                if (this.type === 'general' || this.mode !== 'multi') return false;
                // Only 'she', not 'she_plus'
                return this.selectedStreams.includes('she');
            }
        }
    }
</script>
@endsection
