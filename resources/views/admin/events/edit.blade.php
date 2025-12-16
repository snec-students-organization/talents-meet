@extends('layouts.app')

@section('content')

<div class="container py-5">

    <h1 class="fw-bold mb-4" style="color:#013A63;">Edit Event: {{ $event->name }}</h1>

    <form action="{{ route('admin.events.update', $event->id) }}" method="POST"
        x-data="eventForm()">
        @csrf
        @method('PUT')

        <!-- MAIN FORM CARD -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body">

                <!-- EVENT NAME -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
                </div>

                <!-- CATEGORY -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach(['A','B','C','D'] as $c)
                            <option value="{{ $c }}" {{ old('category', $event->category) == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- STAGE TYPE -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Stage Type</label>
                    <select name="stage_type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="stage" {{ old('stage_type', $event->stage_type) == 'stage' ? 'selected' : '' }}>Stage Event</option>
                        <option value="non_stage" {{ old('stage_type', $event->stage_type) == 'non_stage' ? 'selected' : '' }}>Non-Stage Event</option>
                    </select>
                </div>

                <!-- EVENT TYPE -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Type</label>
                    <select name="type" class="form-select" x-model="type" required>
                        <option value="">Select Type</option>
                        <option value="individual">Individual</option>
                        <option value="group">Group</option>
                        <option value="general">General</option>
                    </select>
                </div>

                <!-- STREAM -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Stream</label>
                    <select name="stream" class="form-select" x-model="stream" required>
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

                <!-- LEVEL (SHOW ONLY FOR SHARIA/SHE & NON-GENERAL) -->
                <div class="mb-3" x-show="showLevel">
                    <label class="form-label fw-semibold">Level</label>
                    <select name="level" class="form-select">
                        <option value="">Select Level</option>
                        <option value="Sanaviyya Ulya" {{ old('level', $event->level) == 'Sanaviyya Ulya' ? 'selected' : '' }}>Sanaviyya Ulya</option>
                        <option value="Bakalooriyya" {{ old('level', $event->level) == 'Bakalooriyya' ? 'selected' : '' }}>Bakalooriyya</option>
                        <option value="Majestar" {{ old('level', $event->level) == 'Majestar' ? 'selected' : '' }}>Majestar</option>
                    </select>
                </div>

            </div>
        </div>

        <!-- RULES CARD -->
        <div class="card shadow border-0 mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Event Rules & Restrictions</h5>
            </div>

            <div class="card-body">

                <!-- Allowed Streams -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Allowed Streams (Optional)</label>
                    @php
                        $allowed = is_array($event->allowed_streams) ? $event->allowed_streams : json_decode($event->allowed_streams, true) ?? [];
                    @endphp
                    <div class="row">
                        @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath'] as $s)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           name="allowed_streams[]" value="{{ $s }}"
                                           {{ in_array($s, $allowed) ? 'checked' : '' }}>
                                    <label class="form-check-label text-capitalize">
                                        {{ str_replace('_',' ', $s) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Max Participants (GROUP ONLY) -->
                <div class="mb-3" x-show="type === 'group'">
                    <label class="form-label fw-semibold">Maximum Participants (Group Event)</label>
                    <input type="number" name="max_participants" min="1" max="20"
                           class="form-control" value="{{ old('max_participants', $event->max_participants) }}">
                </div>

                <!-- Max Entries -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Max Entries per Institution</label>
                    <input type="number" name="max_institution_entries" min="1"
                           class="form-control" value="{{ old('max_institution_entries', $event->max_institution_entries ?? 1) }}">
                </div>

            </div>
        </div>

        <!-- SUBMIT -->
        <button class="btn btn-primary px-4 py-2" style="background:#013A63; border:none;">
            Update Event
        </button>

    </form>

</div>

<!-- 🔥 ALPINE LOGIC -->
<script>
    function eventForm() {
        return {
            type: '{{ old('type', $event->type) }}',
            stream: '{{ old('stream', $event->stream) }}',
            get showLevel() {
                return this.type !== 'general' &&
                       (this.stream === 'sharia' || this.stream === 'she');
            }
        }
    }
</script>

@endsection
