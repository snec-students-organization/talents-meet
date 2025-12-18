<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;

class RegistrationOverviewController extends Controller
{
    public function index()
    {
        // Get all events with registrations and related student + institution
        $events = Event::with(['registrations.student', 'registrations.institution'])->get();

        return view('admin.registrations.index', compact('events'));
    }

    public function show(Event $event)
    {
        // Load the event with its registrations, students, and institutions
        $event->load(['registrations.student', 'registrations.institution']);

        return view('admin.registrations.show', compact('event'));
    }
}
