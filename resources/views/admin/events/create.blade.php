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

                {{-- STREAM --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Stream</label>
                    <select name="stream" x-model="stream" required
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

                {{-- LEVEL --}}
                <div x-show="showLevel" x-transition class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Level (Optional)</label>
                    <select name="level"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <option value="">Select Level</option>
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
            type: '',
            stream: '',
            get showLevel() {
                // Show level for all streams except 'general' type or 'bayyinath'/'life' if they don't have levels
                // Based on seeder: sharia, sharia_plus, she, she_plus, life_plus have levels.
                // life and bayyinath do not.
                if (this.type === 'general') return false;
                return ['sharia', 'sharia_plus', 'she', 'she_plus', 'life_plus'].includes(this.stream);
            }
        }
    }
</script>
@endsection
