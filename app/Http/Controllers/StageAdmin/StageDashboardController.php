<?php

namespace App\Http\Controllers\StageAdmin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StageDashboardController extends Controller
{
    /**
     * Show stage selection page (on first login)
     */
    public function selectStage()
    {
        return view('stage_admin.select_stage');
    }

    /**
     * Store selected stage number in session
     */
    public function setStage(Request $request)
    {
        $request->validate([
            'stage_number' => 'required|integer|min:1|max:5'
        ]);

        session(['stage_number' => $request->stage_number]);

        return redirect()->route('stage_admin.dashboard');
    }

    /**
     * Show all events assigned to this stage
     */
    public function index()
    {
        $stageNumber = session('stage_number');

        if (!$stageNumber) {
            return redirect()->route('stage_admin.select_stage')
                ->with('error', 'Please select your stage first.');
        }

        $events = Event::where('stage_number', $stageNumber)
            ->where('stage_type', 'stage')
            ->orderBy('category')
            ->get();

        // Calculate completed and pending assignments
        $completedCount = 0;
        $pendingCount = 0;

        foreach ($events as $event) {
            // Check if this event has at least one registration with a chest number
            $hasChestNumbers = Registration::where('event_id', $event->id)
                ->whereNotNull('code_letter')
                ->exists();
            
            // Add completion status to event object
            $event->is_completed = $hasChestNumbers;
            
            if ($hasChestNumbers) {
                $completedCount++;
            } else {
                $pendingCount++;
            }
        }

        return view('stage_admin.dashboard', compact('events', 'stageNumber', 'completedCount', 'pendingCount'));
    }

    /**
     * View participants of a specific event
     */
    public function viewEvent($id)
    {
        $event = Event::findOrFail($id);
        $registrations = Registration::with(['student', 'institution'])
            ->where('event_id', $id)
            ->get();

        return view('stage_admin.events.view', compact('event', 'registrations'));
    }

    /**
     * Save assigned code letters for participants
     */
    public function saveCodes(Request $request, $id)
    {
        $request->validate([
            'codes' => 'array',
        ]);

        foreach ($request->codes as $registrationId => $codeLetter) {
            if ($codeLetter !== null && $codeLetter !== '') {
                Registration::where('id', $registrationId)
                    ->update(['code_letter' => strtoupper($codeLetter)]);
            }
        }

        return back()->with('success', 'Codes assigned successfully!');
    }
}
