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
    public function index(Request $request)
    {
        $institution = Auth::user();
        $stream = $institution->stream;
        $userLevels = $institution->levels ?? []; // e.g. ['Sanaviyya', 'Aliya']

        // Filter by Type (Tabs)
        $type = $request->query('type', 'individual'); // Default: individual

        $query = Event::query();

        $query->with(['registrations' => function ($q) use ($institution) {
            $q->where('institution_id', $institution->id)->with('student');
        }]);

        if ($type === 'general') {
            $query->where('type', 'general');
        } elseif ($type === 'off_stage') {
            $query->where('stream', $stream)
                  ->where('stage_type', 'non_stage');
             
             // Level Filtering for Off-Stage
             if (!empty($userLevels)) {
                 $query->where(function($q) use ($userLevels) {
                     $q->whereNull('level')
                       ->orWhereIn('level', $userLevels);
                 });
             }
        } else {
            // Stage Events (Individual or Group)
            $query->where('stream', $stream)
                  ->where('stage_type', 'stage')
                  ->where('type', $type);
            
            // Level Filtering for Stage Events
            if (!empty($userLevels)) {
                 $query->where(function($q) use ($userLevels) {
                     $q->whereNull('level')
                       ->orWhereIn('level', $userLevels);
                 });
            }
        }
        
        $events = $query->orderBy('category')->get();

        return view('institution.events.index', compact('events', 'type'));
    }


    /* ============================================================
        2. SHOW REGISTER FORM
    ============================================================ */
    public function registerForm($eventId)
    {
        $event = Event::findOrFail($eventId);
        $institutionId = Auth::id();
        
        // Load existing registrations for pre-filling
        $existingRegistrations = Registration::with('student')
            ->where('event_id', $eventId)
            ->where('institution_id', $institutionId)
            ->get();

        return view('institution.events.register', compact('event', 'existingRegistrations'));
    }


    /* ============================================================
        3. REGISTER STUDENT FOR EVENT
    ============================================================ */
    public function register(Request $request, $eventId)
    {
        $request->validate([
            'participants' => 'nullable|array', // Nullable because they might clear all rows (though min:1 usually preferred)
            'participants.*.uid' => 'nullable|string|max:50', // Allow empty rows to be ignored
            'participants.*.name' => 'nullable|string|max:255',
            'participants.*.gender' => 'nullable|string',
        ]);

        $event = Event::findOrFail($eventId);
        $institutionId = Auth::id();

        // 1. Get current registered student IDs for this event
        $currentRegistrationIds = Registration::where('event_id', $eventId)
            ->where('institution_id', $institutionId)
            ->pluck('student_id')
            ->toArray();

        $submittedStudentIds = [];
        $registeredCount = 0;

        // 2. Process submitted participants
        if ($request->has('participants')) {
            foreach ($request->participants as $participantData) {
                // Skip empty rows
                if (empty($participantData['uid']) || empty($participantData['name'])) continue;

                // Find or Create Student using UID
                // (Assuming UID is unique per student across system or at least inst scope)
                $student = Student::updateOrCreate(
                    ['uid' => $participantData['uid'], 'institution_id' => $institutionId],
                    [
                        'name' => $participantData['name'],
                        'gender' => $participantData['gender'] ?? null
                    ]
                );

                $submittedStudentIds[] = $student->id;

                // Ensure Registration exists
                if (!Registration::where('event_id', $eventId)
                        ->where('student_id', $student->id)
                        ->exists()) 
                {
                    Registration::create([
                        'event_id' => $event->id,
                        'student_id' => $student->id,
                        'institution_id' => $institutionId,
                    ]);
                    $registeredCount++;
                }
            }
        }

        // 3. Remove registrations that are NOT in the submitted list (Sync deletion)
        $studentsToRemove = array_diff($currentRegistrationIds, $submittedStudentIds);
        
        if (!empty($studentsToRemove)) {
            Registration::where('event_id', $eventId)
                ->where('institution_id', $institutionId)
                ->whereIn('student_id', $studentsToRemove)
                ->delete();
        }

        $removedCount = count($studentsToRemove);
        $message = "Registration updated! ";
        if ($registeredCount > 0) $message .= "$registeredCount added. ";
        if ($removedCount > 0) $message .= "$removedCount removed.";
        if ($registeredCount == 0 && $removedCount == 0) $message = "No changes made.";

        return redirect()->route('institution.events.index')
            ->with('success', $message);
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
            ->has('registrations') // Only show students active in at least one event
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
