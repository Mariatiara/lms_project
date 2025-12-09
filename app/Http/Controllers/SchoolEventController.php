<?php

namespace App\Http\Controllers;

use App\Models\SchoolEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('academic.calendar');
    }

    /**
     * Get events for FullCalendar
     */
    /**
     * Helper to get Current School ID
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        return $user->school_id ?? $user->teacher?->school_id ?? $user->student?->school_id;
    }

    /**
     * Get events for FullCalendar
     */
    public function getEvents(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([]);
        }

        $events = SchoolEvent::where('school_id', $schoolId)
            ->whereDate('start_date', '>=', $start)
            ->whereDate('end_date', '<=', $end)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->toIso8601String(),
                    'end' => $event->end_date->toIso8601String(),
                    'backgroundColor' => $this->getColorByType($event->type),
                    'borderColor' => $this->getColorByType($event->type),
                    'extendedProps' => [
                        'type' => $event->type,
                        'description' => $event->description,
                        'is_holiday' => $event->is_holiday,
                    ],
                ];
            });

        return response()->json($events);
    }

    private function getColorByType($type)
    {
        return match ($type) {
            'holiday' => '#ef4444', // Red
            'exam' => '#f59e0b', // Orange
            'meeting' => '#3b82f6', // Blue
            'event' => '#10b981', // Green
            default => '#6b7280', // Gray
        };
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
             abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:holiday,exam,event,meeting,other',
            'description' => 'nullable|string',
            'is_holiday' => 'boolean',
        ]);
        
        $event = SchoolEvent::create([
            'school_id' => $schoolId,
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'description' => $request->description,
            'is_holiday' => $request->boolean('is_holiday'),
        ]);

        return response()->json(['message' => 'Event created successfully', 'event' => $event]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SchoolEvent $schoolEvent)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId || $schoolEvent->school_id !== $schoolId) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:holiday,exam,event,meeting,other',
            'description' => 'nullable|string',
            'is_holiday' => 'boolean',
        ]);

        $schoolEvent->update([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'description' => $request->description,
            'is_holiday' => $request->boolean('is_holiday'),
        ]);

        return response()->json(['message' => 'Event updated successfully', 'event' => $schoolEvent]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SchoolEvent $schoolEvent)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId || $schoolEvent->school_id !== $schoolId) {
            abort(403);
        }

        $schoolEvent->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
