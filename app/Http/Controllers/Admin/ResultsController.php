<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\JudgeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// add at top of file with other use statements
use App\Models\User;
use App\Models\Student;


class ResultsController extends Controller
{
    // Streams we manage (matches events.stream enum)
    protected $streams = [
        'sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'
    ];

    // Rank points by category
    protected $rankPoints = [
        'A' => [1 => 10, 2 => 7, 3 => 5],
        'B' => [1 => 7, 2 => 5, 3 => 3],
        'C' => [1 => 5, 2 => 3, 3 => 1],
        'D' => [1 => 20,2 => 15,3 => 10],
    ];

    // Grade bonus mapping
    protected $gradePoints = ['A' => 5, 'B' => 3, 'C' => 1];

    // Show admin result dashboard
    public function index(Request $request)
{
    $stream = $request->query('stream', 'sharia'); // default
    $eventId = $request->query('event_id');

    // List all streams
    $streams = [
        'sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'
    ];

    // List all events for this stream
    $eventList = Event::where('stream', $stream)
        ->orderBy('name')
        ->get();

    // If filtering by one event
    $events = $eventId
        ? Event::where('id', $eventId)->get()
        : $eventList;

    // Stream results table
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

    // BEST PARTICIPANT PER INSTITUTION PER EVENT
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

    // Group for admin view
    $eventRanks = $details->groupBy('event_id')->map(function ($eventGroup) {
        return $eventGroup->groupBy('institution_id')->map(function ($instGroup) {
            return $instGroup->sortByDesc('avg_score')->first();
        });
    });

    return view('admin.results.index', compact(
        'stream', 'streams', 'eventList', 'events',
        'rows', 'eventRanks', 'eventId'
    ));
}


    // CALCULATE RESULTS
    public function calculate(Request $request)
{
    $stream = $request->input('stream');
    if (!in_array($stream, $this->streams)) abort(404);

    DB::transaction(function() use ($stream) {

        $table = "{$stream}_results";

        // Remove previous draft results only
        DB::table($table)->where('confirmed', false)->delete();

        // 1. GET ALL EVENTS (STAGE + NON-STAGE)
        $events = Event::where('stream', $stream)
            ->orderBy('category')
            ->get();

        $institutionTotals = [];
        $breakdown = [];

        foreach ($events as $event) {

            // 2. AVERAGE SCORE PER PARTICIPANT
            $regAverages = JudgeScore::select('registration_id', DB::raw('AVG(score) as avg_score'))
                ->where('event_id', $event->id)
                ->groupBy('registration_id')
                ->orderByDesc('avg_score')
                ->get();

            // 3. HIGHEST GRADE PER PARTICIPANT
            $gradesPerReg = JudgeScore::select('registration_id', 'grade')
                ->where('event_id', $event->id)
                ->get()
                ->groupBy('registration_id')
                ->mapWithKeys(function ($items, $regId) {
                    $priority = ['A'=>3,'B'=>2,'C'=>1];
                    $best = null; 
                    $bestVal = 0;

                    foreach ($items as $it) {
                        $g = $it->grade;
                        if ($g && ($priority[$g] ?? 0) > $bestVal) {
                            $best = $g;
                            $bestVal = $priority[$g];
                        }
                    }
                    return [$regId => $best];
                });

            // 4. RANKING
            $rank = 1;
            $prevScore = null;
            foreach ($regAverages as $row) {
                $avg = (float)$row->avg_score;
                if ($prevScore !== null && $avg < $prevScore) {
                    $rank++;
                }
                $row->rank = $rank;
                $prevScore = $avg;
            }

            // 5. AWARD POINTS
            foreach ($regAverages as $row) {

                $regId = $row->registration_id;

                $rank = $row->rank;
                $rankPoint = ($this->rankPoints[$event->category][$rank] ?? 0);

                $registration = Registration::with('student')->find($regId);
                if (!$registration) continue;

                $institutionId = $registration->institution_id;

                $grade = $gradesPerReg[$regId] ?? null;
                $gradeBonus = $grade ? ($this->gradePoints[$grade] ?? 0) : 0;

                $totalForReg = $rankPoint + $gradeBonus;

                // ADD TO TOTALS
                $institutionTotals[$institutionId] =
                    ($institutionTotals[$institutionId] ?? 0) + $totalForReg;

                // SAVE BREAKDOWN
                $breakdown[$institutionId][] = [
                    'institution_name' => $registration->institution->name ?? 'Unknown',
                    'event_name' => $event->name,
                    'category'   => $event->category,
                    'uid'        => $registration->student->uid ?? '-',
                    'name'       => $registration->student->name ?? '-',
                    'grade'      => $grade,
                    'rank'       => $rank,
                    'points'     => $rankPoint,
                    'grade_bonus'=> $gradeBonus,
                    'total'      => $totalForReg,
                ];
            }
        }

        // 6. STORE RESULTS
        foreach ($institutionTotals as $instId => $points) {

            $existing = DB::table($table)
                ->where('institution_id', $instId)
                ->first();

            if ($existing && !$existing->confirmed) {
                DB::table($table)->where('institution_id', $instId)->update([
                    'total_points' => $points,
                    'updated_at' => now(),
                ]);
            } elseif (!$existing) {
                DB::table($table)->insert([
                    'institution_id' => $instId,
                    'total_points' => $points,
                    'confirmed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // SAVE BREAKDOWN FOR PUBLIC DISPLAY
        session(["breakdown_{$stream}" => $breakdown]);
    });

    return back()->with('success', "Results calculated for stream {$stream}.");
}


    // PUBLISH RESULTS
    public function publish(Request $request)
    {
        $stream = $request->input('stream');
        $table = "{$stream}_results";

        DB::table($table)->update([
            'confirmed' => true,
            'updated_at' => now()
        ]);

        return back()->with('success', "Published results for stream: {$stream}.");
    }

    // RESET results
    public function reset(Request $request)
    {
        $stream = $request->input('stream');
        $table = "{$stream}_results";

        DB::table($table)->where('confirmed', false)->delete();

        session()->forget("breakdown_{$stream}");

        return back()->with('success', "Draft results cleared for stream: {$stream}.");
    }
    public function scoreboard($stream)
{
    if (!in_array($stream, $this->streams)) {
        abort(404);
    }

    // 1. LOAD ALL EVENTS OF THIS STREAM
    $events = Event::where('stream', $stream)
        ->orderBy('name')
        ->get();

    if ($events->isEmpty()) {
        return back()->with('error', 'No events found for this stream.');
    }

    // 2. LOAD ALL INSTITUTIONS WITH PUBLISHED RESULTS
    $table = "{$stream}_results";

    $institutions = DB::table($table)
        ->join('users', 'users.id', "{$table}.institution_id")
        ->where("{$table}.confirmed", true)
        ->select(
            "{$table}.institution_id",
            'users.name as institution_name'
        )
        ->orderBy('institution_name')
        ->get();

    // 3. BUILD MATRIX (institution × event)
    $matrix = [];

    foreach ($institutions as $inst) {
        foreach ($events as $event) {
            $matrix[$inst->institution_id][$event->id] = 0; // default
        }
    }

    // 4. GET SCORES DIRECTLY FROM DATABASE (NO SESSION)
    $scores = JudgeScore::join('registrations', 'registrations.id', 'judge_scores.registration_id')
        ->join('students', 'students.id', 'registrations.student_id')
        ->join('events', 'events.id', 'judge_scores.event_id')
        ->where('events.stream', $stream)
        ->select(
            'judge_scores.registration_id',
            'judge_scores.event_id',
            'judge_scores.grade',
            'judge_scores.score',
            'registrations.institution_id',
            'students.uid',
            'students.name',
            'events.category'
        )
        ->get();

    // 5. CALCULATE RANKS + POINTS PER EVENT
    foreach ($events as $event) {

        $eventScores = $scores->where('event_id', $event->id);

        // average score per registration
        $grouped = $eventScores->groupBy('registration_id')->map(function ($rows) {
            return $rows->avg('score');
        });

        // sort by score descending
        $sorted = $grouped->sortDesc();

        // assign ranks
        $rank = 1;
        $previous = null;
        $rankMap = [];

        foreach ($sorted as $regId => $avgScore) {
            if ($previous !== null && $avgScore < $previous) {
                $rank++;
            }
            $rankMap[$regId] = $rank;
            $previous = $avgScore;
        }

        // assign points
        foreach ($eventScores as $row) {

            $rank = $rankMap[$row->registration_id] ?? null;

            // rank points
            $rankPoints = 0;
            if (in_array($rank, [1,2,3])) {
                $rankPoints = $this->rankPoints[$event->category][$rank] ?? 0;
            }

            // grade bonus
            $gradeBonus = $this->gradePoints[$row->grade] ?? 0;

            $totalPoints = $rankPoints + $gradeBonus;

            // add to matrix
            if (!isset($matrix[$row->institution_id][$event->id])) {
                $matrix[$row->institution_id][$event->id] = 0;
            }

            // accumulate
            $matrix[$row->institution_id][$event->id] += $totalPoints;
        }
    }

    return view('admin.results.scoreboard', compact(
        'stream', 'events', 'institutions', 'matrix'
    ));
}
public function nonStageScoreboard(Request $request, $stream)
{
    $eventId = $request->query('event_id');

    // list of events to filter
    $eventList = Event::where('stream', $stream)
        ->where('stage_type', 'non_stage')
        ->orderBy('name')
        ->get();

    // base query
    $query = Registration::select(
            'registrations.*',
            'students.uid',
            'students.name as student_name',
            'users.name as institution_name',
            'events.name as event_name',
            'events.category',
            DB::raw('AVG(judge_scores.score) as avg_score'),
            DB::raw('MAX(judge_scores.grade) as grade')
        )
        ->join('students', 'students.id', 'registrations.student_id')
        ->join('users', 'users.id', 'registrations.institution_id')
        ->join('events', 'events.id', 'registrations.event_id')
        ->leftJoin('judge_scores', 'judge_scores.registration_id', 'registrations.id')
        ->where('events.stream', $stream)
        ->where('events.stage_type', 'non_stage')
        ->groupBy(
            'registrations.id',
            'students.uid',
            'students.name',
            'users.name',
            'events.name',
            'events.category'
        );

    if ($eventId) {
        $query->where('registrations.event_id', $eventId);
    }

    $scoreboard = $query->orderByDesc('avg_score')->get();

    // assign rank
    $rank = 1;
    $prevScore = null;

    foreach ($scoreboard as $item) {
        if ($prevScore !== null && $item->avg_score < $prevScore) {
            $rank++;
        }
        $item->rank = $rank;
        $prevScore = $item->avg_score;
    }

    // ⭐ NEW: selected event details for header card
    $selectedEvent = $eventId ? Event::find($eventId) : null;

    return view('admin.results.non_stage_scoreboard', compact(
        'stream', 'eventList', 'eventId', 'scoreboard', 'selectedEvent'
    ));
}
public function eventsDetails()
{
    $streams = ['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'];

    $events = Event::orderBy('stream')->orderBy('name')->get()
                ->groupBy('stream');

    return view('admin.details.events_by_stream', compact('events','streams'));
}
public function institutionsDetails()
{
    $institutions = \App\Models\User::where('role','institution')
                    ->orderBy('stream')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('stream');

    return view('admin.details.institutions_by_stream', compact('institutions'));
}
public function participantsDetails()
{
    $streams = ['sharia','sharia_plus','she','she_plus','life','life_plus','bayyinath','general'];

    $institutions = \App\Models\User::where('role','institution')
                    ->with(['students.registrations.event'])
                    ->orderBy('stream')
                    ->orderBy('name')
                    ->get()
                    ->groupBy('stream');

    return view('admin.details.participants_by_stream', compact('institutions','streams'));
}
/**
 * Show events grouped by stream (for Admin -> Total Events card)
 */
public function eventsByStream()
{
    // fetch events grouped by stream
    $events = Event::orderBy('stream')
                   ->orderBy('name')
                   ->get()
                   ->groupBy('stream');

    return view('admin.details.events_by_stream', compact('events'));
}

/**
 * Show institutions grouped by stream (for Admin -> Total Institutions card)
 */
public function institutionsByStream()
{
    $institutions = User::where('role', 'institution')
                        ->orderBy('stream')
                        ->orderBy('name')
                        ->get()
                        ->groupBy('stream');

    return view('admin.details.institutions_by_stream', compact('institutions'));
}

/**
 * Show participants grouped by stream -> institution -> students (for Admin -> Total Participants)
 */
public function participantsByStream()
{
    // load institutions with their students and each student's registrations with event
    $institutions = User::where('role', 'institution')
                        ->with([
                            'students.registrations.event' => function($q) {
                                $q->select('id','name','category','stream','stage_type');
                            },
                            'students' => function($q) {
                                $q->select('id','uid','name','institution_id');
                            }
                        ])
                        ->orderBy('stream')
                        ->orderBy('name')
                        ->get()
                        ->groupBy('stream');

    return view('admin.details.participants_by_stream', compact('institutions'));
}






}
