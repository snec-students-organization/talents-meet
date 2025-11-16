<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Student;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;  
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventRegistrationController extends Controller
{
    /* ============================================================
        1. SHOW AVAILABLE EVENTS
    ============================================================ */
    public function index()
    {
        $institution = Auth::user();

        $events = Event::where(function ($query) use ($institution) {
                $query->where('stream', $institution->stream)
                      ->orWhere('type', 'general');
            })
            ->with(['registrations' => function ($q) use ($institution) {
                $q->where('institution_id', $institution->id)->with('student');
            }])
            ->orderBy('category')
            ->get();

        return view('institution.events.index', compact('events'));
    }


    /* ============================================================
        2. SHOW REGISTER FORM
    ============================================================ */
    public function registerForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $students = Student::where('institution_id', Auth::id())->get();

        return view('institution.events.register', compact('event', 'students'));
    }


    /* ============================================================
        3. REGISTER STUDENT FOR EVENT
    ============================================================ */
    public function register(Request $request, $eventId)
    {
        $request->validate([
            'uid' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'gender' => 'nullable|string',
        ]);

        $event = Event::findOrFail($eventId);
        $institutionId = Auth::id();

        // Check if student exists
        $student = Student::where('uid', $request->uid)
            ->where('institution_id', $institutionId)
            ->first();

        // If not → create student
        if (!$student) {
            $student = Student::create([
                'uid' => $request->uid,
                'name' => $request->name,
                'gender' => $request->gender,
                'institution_id' => $institutionId,
            ]);
        }

        // Prevent duplicate registration
        if (Registration::where('event_id', $eventId)
                ->where('student_id', $student->id)
                ->exists()) 
        {
            return back()->with('error', 'This student is already registered for this event.');
        }

        // Register
        Registration::create([
            'event_id' => $event->id,
            'student_id' => $student->id,
            'institution_id' => $institutionId,
        ]);

        return redirect()->route('institution.events.index')
            ->with('success', 'Student registered successfully!');
    }


    /* ============================================================
        4. MY REGISTRATIONS
    ============================================================ */
    public function myRegistrations()
    {
        $registrations = Registration::with(['event', 'student'])
            ->where('institution_id', Auth::id())
            ->latest()
            ->get();

        return view('institution.events.my_registrations', compact('registrations'));
    }


    /* ============================================================
        5. PDF — FULL EVENT LIST FOR INSTITUTION
    ============================================================ */
    public function downloadAllEventsPDF()
    {
        $institution = Auth::user();

        $registrations = Registration::with(['event', 'student'])
            ->where('institution_id', $institution->id)
            ->orderBy('event_id')
            ->get();

        $pdf = PDF::loadView('institution.pdf.all_events', [
            'institution' => $institution,
            'registrations' => $registrations
        ]);

        return $pdf->download($institution->name . '_Event_List.pdf');
    }


    /* ============================================================
        6. PARTICIPANTS PAGE (STUDENT BASED)
    ============================================================ */
    public function participantsPage()
    {
        $institution = Auth::user();

        // Load unique students + all their events
        $students = Student::where('institution_id', $institution->id)
            ->with(['registrations.event'])
            ->get();

        return view('institution.participants.index', compact('students'));
    }


    /* ============================================================
        7. DOWNLOAD — ALL PARTICIPANTS PDF
    ============================================================ */
    public function downloadAllParticipants()
{
    $institution = Auth::user();

    $participants = Registration::with(['student','event'])
        ->where('institution_id', $institution->id)
        ->get();

    return PDF::loadView('institution.participants.pdf_all', [
        'participants' => $participants,
        'institution' => $institution
    ])->download($institution->name . '_All_Participants.pdf');
}



    /* ============================================================
        8. DOWNLOAD — SINGLE STUDENT ID CARD (ALL EVENTS)
    ============================================================ */
    public function downloadStudentCard($studentId)
{
    $institution = Auth::user();

    // Load student + all their event registrations
    $student = Student::where('id', $studentId)
        ->where('institution_id', $institution->id)
        ->with(['registrations.event'])   // <-- Load events here
        ->firstOrFail();

    // Generate QR for UID (SVG = no imagick required)
    $qr = base64_encode(
        \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
            ->size(150)
            ->generate($student->uid)
    );

    // Render PDF
    $pdf = \PDF::loadView('institution.participants.pdf_student', [
        'student'       => $student,
        'institution'   => $institution,
        'qr'            => $qr,
        'events'        => $student->registrations   // <-- send events list
    ])->setPaper('A6', 'portrait');

    return $pdf->download($student->uid . '_ID_Card.pdf');
}


}
