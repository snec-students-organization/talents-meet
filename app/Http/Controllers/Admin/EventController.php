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
            'mode' => 'nullable|string|in:single,multi',
            // Stream is required if mode is single or unset
            'stream' => 'required_if:mode,single|nullable|string',
            // Streams array required if mode is multi
            'streams' => 'required_if:mode,multi|array',
            
            'level' => 'nullable|string',
            'sharia_level' => 'nullable|string',
            'she_level' => 'nullable|string',
            'allowed_streams' => 'nullable|array',
            'max_participants' => 'nullable|integer',
            'max_institution_entries' => 'nullable|integer',
        ]);

        // Convert allowed_streams array to JSON if provided
        $allowedStreams = $request->allowed_streams
            ? json_encode($request->allowed_streams)
            : null;

        $createdBy = auth()->id();
        $count = 0;

        if ($request->mode === 'multi' && !empty($request->streams)) {
            // MULTI STREAM CREATION
            foreach ($request->streams as $stream) {
                $data = $validated;
                $data['stream'] = $stream;
                $data['allowed_streams'] = $allowedStreams;
                $data['created_by'] = $createdBy;
                
                // Determine Level based on stream
                $data['level'] = null; // Default null
                
                if ($stream === 'sharia') {
                     $data['level'] = $request->sharia_level;
                } elseif ($stream === 'she') {
                     $data['level'] = $request->she_level;
                }
                // Note: Other streams (life_plus etc) will have null level unless we add more logic.
                // Request specifically asked for separation of Sharia/She options.

                unset($data['streams'], $data['mode'], $data['sharia_level'], $data['she_level']); // cleanup

                Event::create($data);
                $count++;
            }
            $message = "$count events added successfully!";

        } else {
            // SINGLE STREAM CREATION
            $validated['allowed_streams'] = $allowedStreams;
            $validated['created_by'] = $createdBy;
            unset($validated['mode'], $validated['streams']);

            Event::create($validated);
            $message = 'Event added successfully!';
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', $message);
    }
    
    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
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

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
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

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
