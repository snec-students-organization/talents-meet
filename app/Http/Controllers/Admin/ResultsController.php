<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\JudgeScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

            // Remove old draft results (keep confirmed ones)
            DB::table($table)->where('confirmed', false)->delete();

            $events = Event::where('stream', $stream)->get();

            $institutionTotals = [];
            $breakdown = []; // <-- save ALL event details

            foreach ($events as $event) {

                // Find AVG score per registration (per event)
                $regAverages = JudgeScore::select('registration_id', DB::raw('AVG(score) as avg_score'))
                    ->where('event_id', $event->id)
                    ->groupBy('registration_id')
                    ->orderByDesc('avg_score')
                    ->get();

                // Get BEST grade for each registration
                $gradesPerReg = JudgeScore::where('event_id', $event->id)
                    ->get()
                    ->groupBy('registration_id')
                    ->mapWithKeys(function($items, $regId) {
                        $priority = ['A'=>3,'B'=>2,'C'=>1];
                        $best = null; $bestVal = 0;
                        foreach ($items as $it) {
                            $g = $it->grade;
                            if ($g && ($priority[$g] ?? 0) > $bestVal) {
                                $best = $g; $bestVal = $priority[$g];
                            }
                        }
                        return [$regId => $best];
                    });

                // Rank logic
                $rank = 1;
                $previousScore = null;

                foreach ($regAverages as $row) {

                    $avg = (float)$row->avg_score;

                    if ($previousScore !== null && $avg < $previousScore) {
                        $rank++;
                    }

                    $row->rank = $rank;
                    $previousScore = $avg;
                }

                // Assign Points
                foreach ($regAverages as $row) {

                    $regId = $row->registration_id;
                    $rank = $row->rank;

                    $registration = Registration::with('student')->find($regId);
                    if (!$registration) continue;

                    $institutionId = $registration->institution_id;

                    // Rank points only for top 3
                    $rankPoint = 0;
                    if (in_array($rank, [1,2,3])) {
                        $rankPoint = $this->rankPoints[$event->category][$rank] ?? 0;
                    }

                    // Grade bonus
                    $grade = $gradesPerReg[$regId] ?? null;
                    $gradeBonus = $grade ? ($this->gradePoints[$grade] ?? 0) : 0;

                    // Total
                    $totalForReg = $rankPoint + $gradeBonus;

                    // Add to college total
                    $institutionTotals[$institutionId] = ($institutionTotals[$institutionId] ?? 0) + $totalForReg;

                    // SAVE BREAKDOWN
                    $breakdown[$institutionId][] = [
    'institution_name' => $registration->student->institution->name ?? "--",
    'event_name' => $event->name,
    'category' => $event->category,

    // NEW
    'uid' => $registration->student->uid ?? '-',
    'name' => $registration->student->name ?? '-',
    'grade' => $grade ?? '-',   // A / B / C or -

    'rank' => $rank,
    'points' => $rankPoint,
    'grade_bonus' => $gradeBonus,
    'total' => $totalForReg,
];


                }
            }

            // Insert/Update Results Table
            foreach ($institutionTotals as $instId => $points) {

                $exists = DB::table($table)->where('institution_id', $instId)->first();

                if ($exists && !$exists->confirmed) {
                    DB::table($table)->where('institution_id', $instId)->update([
                        'total_points' => $points,
                        'updated_at' => now(),
                    ]);
                } elseif (!$exists) {
                    DB::table($table)->insert([
                        'institution_id' => $instId,
                        'total_points' => $points,
                        'confirmed' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Save breakdown in session for admin & public views
            session(["breakdown_{$stream}" => $breakdown]);
        });

        return back()->with('success', "Calculated results for stream: {$stream}. Review and publish when ready.");
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


}
