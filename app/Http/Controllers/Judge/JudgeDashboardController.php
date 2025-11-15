<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\JudgeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class JudgeDashboardController extends Controller
{
    // Show stage selection on first login
    public function selectStage()
    {
        return view('judge.select_stage');
    }

    // Save selected stage in session
    public function setStage(Request $request)
    {
        $request->validate(['stage_number' => 'required|integer|min:1|max:5']);
        session(['judge_stage' => $request->stage_number]);

        return redirect()->route('judge.dashboard');
    }

    // Show all events assigned to the judge's selected stage
    public function index()
    {
        $stage = session('judge_stage');
        if (!$stage) {
            return redirect()->route('judge.select_stage')
                ->with('error', 'Please select your stage first.');
        }

        $events = Event::where('stage_number', $stage)
            ->where('stage_type', 'stage')
            ->orderBy('category')
            ->get();

        return view('judge.dashboard', compact('events', 'stage'));
    }

    // Show participants (by code letter only) for a specific event
    // Show participants (with existing scores and grades)
public function viewEvent($eventId)
{
    $event = Event::findOrFail($eventId);

    // Registrations with assigned code letters
    $registrations = Registration::with('student')
        ->where('event_id', $eventId)
        ->whereNotNull('code_letter')
        ->get();

    // Existing scores and grades for this judge
    $existingScores = JudgeScore::where('judge_id', Auth::id())
        ->where('event_id', $eventId)
        ->get()
        ->keyBy('registration_id')
        ->map(function ($score) {
            return [
                'score' => $score->score,
                'grade' => $score->grade,
            ];
        });

    return view('judge.event_scores', compact('event', 'registrations', 'existingScores'));
}


// Save scores and grades
public function submitMarks(Request $request, $eventId)
{
    $request->validate([
        'scores' => 'required|array',
        'scores.*' => 'nullable|numeric|min:0|max:100',
        'grades' => 'nullable|array',
        'grades.*' => 'nullable|string|max:2',
    ]);

    foreach ($request->scores as $registrationId => $score) {
        $grade = $request->grades[$registrationId] ?? null;

        JudgeScore::updateOrCreate(
            [
                'judge_id' => auth()->id(),
                'event_id' => $eventId,
                'registration_id' => $registrationId,
            ],
            [
                'score' => $score,
                'grade' => $grade,
            ]
        );
    }

    return back()->with('success', 'Scores and grades saved successfully!');
}
public function scoresList()
{
    $judgeStage = session('judge_stage');

    // Get all scores for this judge stage
    $eventIds = Event::where('stage_number', $judgeStage)->pluck('id');

$scores = JudgeScore::with(['registration.student'])
            ->whereIn('event_id', $eventIds)
            ->orderBy('score', 'desc')
            ->get();


    // Get events for this stage
    $events = Event::where('stage_number', $judgeStage)
                ->orderBy('category')
                ->get();

    // Attach ranking to each event group
    foreach ($events as $event) {
        // Filter scores for this event
        $eventScores = $scores->where('event_id', $event->id);

        $rank = 1;
        $previousScore = null;

        foreach ($eventScores as $item) {
            if ($previousScore !== null && $item->score < $previousScore) {
                $rank++;
            }
            $item->rank = $rank;
            $previousScore = $item->score;
        }
    }

    return view('judge.score_list', compact('scores', 'events'));
}
public function nonStageEvents()
{
    $events = Event::where('stage_type', 'non_stage')
        ->orderBy('stream')
        ->orderBy('category')
        ->get();

    return view('judge.nonstage.index', compact('events'));
}
public function viewNonStageEvent($eventId)
{
    $event = Event::findOrFail($eventId);

    $registrations = Registration::with('student')
        ->where('event_id', $eventId)
        ->get();

    $existingScores = JudgeScore::where('judge_id', Auth::id())
        ->where('event_id', $eventId)
        ->get()
        ->keyBy('registration_id')
        ->map(function ($score) {
            return [
                'score' => $score->score,
                'grade' => $score->grade,
            ];
        });

    return view('judge.nonstage.score', compact('event', 'registrations', 'existingScores'));
}
public function submitNonStageMarks(Request $request, $eventId)
{
    $request->validate([
        'scores' => 'required|array',
        'scores.*' => 'nullable|numeric|min:0|max:100',
        'grades' => 'nullable|array',
        'grades.*' => 'nullable|string|max:2',
    ]);

    foreach ($request->scores as $registrationId => $score) {
        $grade = $request->grades[$registrationId] ?? null;

        JudgeScore::updateOrCreate(
            [
                'judge_id' => auth()->id(),
                'event_id' => $eventId,
                'registration_id' => $registrationId,
            ],
            [
                'score' => $score,
                'grade' => $grade,
            ]
        );
    }

    return back()->with('success', 'Scores for Non-Stage Event saved successfully!');
}






}
