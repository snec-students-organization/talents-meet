@extends('layouts.app')

@section('content')

<div class="container py-5">

    <h1 class="fw-bold mb-4" style="color:#013A63;">Add New Event</h1>

    <form action="{{ route('admin.events.store') }}" method="POST"
        x-data="eventForm()">

        @csrf

        <!-- MAIN FORM CARD -->
        <div class="card shadow border-0 mb-4">
            <div class="card-body">

                <!-- EVENT NAME -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Event Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <!-- CATEGORY -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        @foreach(['A','B','C','D'] as $c)
                            <option value="{{ $c }}">{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- STAGE TYPE -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Stage Type</label>
                    <select name="stage_type" class="form-select" required>
                        <option value="">Select</option>
                        <option value="stage">Stage Event</option>
                        <option value="non_stage">Non-Stage Event</option>
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
                        <option value="sanaviyya_ulya">Sanaviyya Ulya</option>
                        <option value="bakalooriyya">Bakalooriyya</option>
                        <option value="majestar">Majestar</option>
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

                    <div class="row">
                        @foreach(['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath'] as $s)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           name="allowed_streams[]" value="{{ $s }}">
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
                           class="form-control">
                </div>

                <!-- Max Entries -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Max Entries per Institution</label>
                    <input type="number" name="max_institution_entries" min="1" value="1"
                           class="form-control">
                </div>

            </div>
        </div>

        <!-- SUBMIT -->
        <button class="btn btn-primary px-4 py-2" style="background:#013A63; border:none;">
            Add Event
        </button>

    </form>

</div>

<!-- 🔥 ALPINE LOGIC -->
<script>
    function eventForm() {
        return {
            type: '',
            stream: '',
            get showLevel() {
                return this.type !== 'general' &&
                       (this.stream === 'sharia' || this.stream === 'she');
            }
        }
    }
</script>

@endsection
