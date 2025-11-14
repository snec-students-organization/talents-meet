<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Institution\EventRegistrationController;
use App\Http\Controllers\Admin\RegistrationOverviewController;
use App\Http\Controllers\StageAdmin\StageDashboardController;
use App\Http\Controllers\Judge\JudgeDashboardController;

// ------------------
// PUBLIC ROUTES
// ------------------
Route::get('/', function () {
    return view('dashboard');
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


// JUDGE DASHBOARD





Route::middleware(['auth', 'role:judge'])->prefix('judge')->group(function () {
    Route::get('/select-stage', [JudgeDashboardController::class, 'selectStage'])->name('judge.select_stage');
    Route::post('/set-stage', [JudgeDashboardController::class, 'setStage'])->name('judge.set_stage');
    Route::get('/dashboard', [JudgeDashboardController::class, 'index'])->name('judge.dashboard');
    Route::get('/event/{id}', [JudgeDashboardController::class, 'viewEvent'])->name('judge.view_event');
    Route::post('/event/{id}/submit', [JudgeDashboardController::class, 'submitMarks'])->name('judge.submitMarks');
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
