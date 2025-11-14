<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Student;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    // 🟢 1. Show all available events
   public function index()
{
    $institution = Auth::user();

    $events = Event::where(function ($query) use ($institution) {
        $query->where('stream', $institution->stream)
              ->orWhere('type', 'general'); // general events visible to all
    })
    ->with(['registrations' => function ($query) use ($institution) {
        $query->where('institution_id', $institution->id)
              ->with('student');
    }])
    ->orderBy('category')
    ->get();

    return view('institution.events.index', compact('events'));
}



    // 🟢 2. Show registration form for a specific event
    public function registerForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $students = Student::where('institution_id', Auth::id())->get();
        return view('institution.events.register', compact('event', 'students'));
    }

    // 🟢 3. Handle registration submission
    public function register(Request $request, $eventId)
{
    $request->validate([
        'uid' => 'required|string|max:50',
        'name' => 'required|string|max:255',
        'gender' => 'nullable|string',
    ]);

    $event = \App\Models\Event::findOrFail($eventId);
    $institutionId = auth()->id();

    // 🧠 Step 1: Find or create student by UID within same institution
    $student = \App\Models\Student::where('uid', $request->uid)
        ->where('institution_id', $institutionId)
        ->first();

    if (!$student) {
        $student = \App\Models\Student::create([
            'uid' => $request->uid,
            'name' => $request->name,
            'gender' => $request->gender,
            'institution_id' => $institutionId,
        ]);
    }

    // 🧠 Step 2: Prevent duplicate registration for same event
    $exists = \App\Models\Registration::where('event_id', $eventId)
        ->where('student_id', $student->id)
        ->exists();

    if ($exists) {
        return back()->with('error', 'This student is already registered for this event.');
    }

    // 🧠 Step 3: Create registration
    \App\Models\Registration::create([
        'event_id' => $event->id,
        'student_id' => $student->id,
        'institution_id' => $institutionId,
    ]);

    return redirect()->route('institution.events.index')->with('success', 'Student registered successfully!');
}



    // 🟢 4. View all registrations by this institution
    public function myRegistrations()
    {
        $registrations = Registration::with(['event', 'student'])
            ->where('institution_id', Auth::id())
            ->latest()
            ->get();

        return view('institution.events.my_registrations', compact('registrations'));
    }
}
