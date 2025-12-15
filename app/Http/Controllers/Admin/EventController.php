<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of all events.
     */
    public function index(Request $request)
    {
        // Show all events with latest first
        $query = Event::latest();

        if ($request->has('type') && in_array($request->type, ['stage', 'non_stage'])) {
            $query->where('stage_type', $request->type);
        }

        $events = $query->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'type' => 'required|string',
            'stage_type' => 'required|in:stage,non_stage',
            'stream' => 'required|string',
            'level' => 'nullable|string',
            'allowed_streams' => 'nullable|array',
            'max_participants' => 'nullable|integer',
            'max_institution_entries' => 'nullable|integer',
        ]);

        // Convert allowed_streams array to JSON if provided
        $validated['allowed_streams'] = $request->allowed_streams
            ? json_encode($request->allowed_streams)
            : null;

        // Save the logged-in admin who created the event
        $validated['created_by'] = auth()->id();

        // Create the event record
        Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event added successfully!');
    }

    /**
     * Assign a stage number (1–5) to an existing event.
     */
    public function assignStage(Request $request, Event $event)
    {
        $request->validate([
            'stage_number' => 'nullable|integer|min:1|max:5',
        ]);

        $event->stage_number = $request->stage_number;
        $event->save();

        return redirect()->back()->with('success', '✅ Stage assigned successfully!');
    }
}
