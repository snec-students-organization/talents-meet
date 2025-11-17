<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Institution\EventRegistrationController;
use App\Http\Controllers\Admin\RegistrationOverviewController;
use App\Http\Controllers\StageAdmin\StageDashboardController;
use App\Http\Controllers\Judge\JudgeDashboardController;
use App\Http\Controllers\Admin\ResultsController;
use App\Models\Registration;
use App\Models\Event;
use App\Models\JudgeScore;
use Illuminate\Support\Facades\DB;
// ------------------
// PUBLIC ROUTES
// ------------------
Route::get('/', function () {
    return view('dashboard');
});




Route::get('/results/{stream}', function($stream) {

    $streams = ['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'];
    if (!in_array($stream, $streams)) abort(404);

    // Get event filter
    $eventId = request('event_id');

    // List of events in this stream
    $eventList = Event::where('stream', $stream)
        ->orderBy('name')
        ->get();

    // MAIN institution ranking table
    $table = "{$stream}_results";

    $rows = DB::table($table)
        ->join('users','users.id',"{$table}.institution_id")
        ->select(
            "{$table}.institution_id",
            "{$table}.total_points",
            'users.name as institution_name'
        )
        ->where('confirmed', true)
        ->orderByDesc('total_points')
        ->get();

    // If there are no published results
    if ($rows->isEmpty()) {
        return view('public.results.stream', [
            'stream' => $stream,
            'eventList' => $eventList,
            'rows' => [],
            'details' => [],
            'eventId' => $eventId
        ]);
    }

    // BEST PARTICIPANT FOR EACH INSTITUTION PER EVENT
    $details = Registration::select(
            'registrations.institution_id',
            'registrations.event_id',
            'students.uid',
            'students.name',
            'events.category',
            'events.name as event_name',
            DB::raw('AVG(judge_scores.score) as avg_score'),
            DB::raw('MAX(judge_scores.grade) as grade')
        )
        ->join('students', 'students.id', 'registrations.student_id')
        ->join('events', 'events.id', 'registrations.event_id')
        ->leftJoin('judge_scores', 'judge_scores.registration_id', 'registrations.id')
        ->where('events.stream', $stream)
        ->groupBy(
            'registrations.institution_id',
            'registrations.event_id',
            'students.uid',
            'students.name',
            'events.category',
            'events.name'
        )
        ->get();

    // Group by event → then institution ID → take top performer
    $eventRanks = $details->groupBy('event_id')->map(function ($eventGroup) {
        return $eventGroup->groupBy('institution_id')->map(function ($instGroup) {
            return $instGroup->sortByDesc('avg_score')->first();
        });
    });

    return view('public.results.stream', [
        'stream' => $stream,
        'eventList' => $eventList,
        'rows' => $rows,
        'eventRanks' => $eventRanks,   // grouped results
        'eventId' => $eventId,
    ]);
});



// ------------------
// REDIRECT AFTER LOGIN
// ------------------


// ------------------
// DASHBOARDS BY ROLE
// ------------------

// ✅ ADMIN DASHBOARD & EVENT MANAGEMENT
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Events
        Route::get('/events', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('admin.events.index');
        Route::get('/events/create', [\App\Http\Controllers\Admin\EventController::class, 'create'])->name('admin.events.create');
        Route::post('/events', [\App\Http\Controllers\Admin\EventController::class, 'store'])->name('admin.events.store');

        // ✅ FIXED: Assign Stage Route (no double prefix)
        Route::post('/events/{event}/assign-stage', [\App\Http\Controllers\Admin\EventController::class, 'assignStage'])
            ->name('admin.events.assignStage');

        // Registrations
        Route::get('/registrations', [\App\Http\Controllers\Admin\RegistrationOverviewController::class, 'index'])
            ->name('admin.registrations.index');
    });


Route::prefix('admin')->middleware(['auth','role:admin'])->group(function() {
    Route::get('results', [ResultsController::class,'index'])->name('admin.results.index');
    Route::post('results/calculate', [ResultsController::class,'calculate'])->name('admin.results.calculate');
    Route::post('results/publish', [ResultsController::class,'publish'])->name('admin.results.publish');
    Route::post('results/reset', [ResultsController::class,'reset'])->name('admin.results.reset');
    Route::get('scoreboard/{stream}', [ResultsController::class, 'scoreboard'])->name('admin.scoreboard');
    Route::get('non-stage/{stream}',  [ResultsController::class, 'nonStageScoreboard'])->name('admin.non_stage_scoreboard');

    
    Route::get('/details/events', [ResultsController::class, 'eventsByStream'])->name('admin.details.events');
    Route::get('/details/institutions', [ResultsController::class, 'institutionsByStream'])->name('admin.details.institutions');
    Route::get('/details/participants', [ResultsController::class, 'participantsByStream'])->name('admin.details.participants');

});






// JUDGE DASHBOARD





Route::middleware(['auth', 'role:judge'])->prefix('judge')->group(function () {
    Route::get('/select-stage', [JudgeDashboardController::class, 'selectStage'])->name('judge.select_stage');
    Route::post('/set-stage', [JudgeDashboardController::class, 'setStage'])->name('judge.set_stage');
    Route::get('/dashboard', [JudgeDashboardController::class, 'index'])->name('judge.dashboard');
    Route::get('/event/{id}', [JudgeDashboardController::class, 'viewEvent'])->name('judge.view_event');
    Route::post('/event/{id}/submit', [JudgeDashboardController::class, 'submitMarks'])->name('judge.submitMarks');
    Route::get('/judge/scores', [JudgeDashboardController::class, 'scoresList'])
    ->name('judge.scores');
    Route::get('/non-stage', [JudgeDashboardController::class, 'nonStageEvents'])
    ->name('judge.nonstage');

Route::get('/non-stage/event/{id}', [JudgeDashboardController::class, 'viewNonStageEvent'])
    ->name('judge.nonstage.event');

Route::post('/non-stage/event/{id}/submit', [JudgeDashboardController::class, 'submitNonStageMarks'])
    ->name('judge.nonstage.submit');


});




// INSTITUTION DASHBOARD
Route::middleware(['auth', 'role:institution'])->group(function () {
    Route::get('/institution/dashboard', function () {
        return view('institution.dashboard');
    })->name('institution.dashboard');
});

Route::middleware(['auth', 'role:institution'])->prefix('institution')->group(function () {
    Route::get('/events', [EventRegistrationController::class, 'index'])->name('institution.events.index');
    Route::get('/events/{id}/register', [EventRegistrationController::class, 'registerForm'])->name('institution.events.registerForm');
    Route::post('/events/{id}/register', [EventRegistrationController::class, 'register'])->name('institution.events.register');
    Route::get('/my-registrations', [EventRegistrationController::class, 'myRegistrations'])->name('institution.events.myRegistrations');

    Route::get('events/download',[EventRegistrationController::class, 'downloadAllEventsPDF'])->name('institution.events.download');
    Route::get('events/card/{id}',[EventRegistrationController::class, 'downloadEventCard'])->name('institution.events.card');
    
    // Institution Participants Landing Page
    Route::get('/participants',[EventRegistrationController::class, 'participantsPage'])->name('institution.participants');
   // Download ALL participants PDF
   Route::get('/participants/download/all',[EventRegistrationController::class, 'downloadAllParticipants'])->name('institution.participants.downloadAll');

// Download Single Participant PDF
   Route::get('/participants/student/{studentId}/download',[EventRegistrationController::class, 'downloadStudentCard'])->name('institution.participants.downloadStudent');


});


// STAGE ADMIN DASHBOARD


// Stage Admin Routes
Route::middleware(['auth', 'role:stage_admin'])->prefix('stage-admin')->name('stage_admin.')->group(function () {
    Route::get('/select-stage', [StageDashboardController::class, 'selectStage'])->name('select_stage');
    Route::post('/set-stage', [StageDashboardController::class, 'setStage'])->name('setStage');
    Route::get('/dashboard', [StageDashboardController::class, 'index'])->name('dashboard');
    Route::get('/event/{id}/view', [StageDashboardController::class, 'viewEvent'])->name('events.view');
    Route::post('/event/{id}/save-codes', [StageDashboardController::class, 'saveCodes'])->name('events.saveCodes');
});


// STUDENT DASHBOARD
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/student/dashboard', function () {
        return view('student.dashboard');
    })->name('student.dashboard');
});

// ------------------
// USER PROFILE (DEFAULT FROM BREEZE)
// ------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ------------------
// AUTH ROUTES
// ------------------
require __DIR__.'/auth.php';
