<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Enums\UserRole;

class ClassScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schoolId = $this->getSchoolId();
        
        if (!$schoolId) {
            abort(403, 'Unauthorized');
        }

        $academicYears = \App\Models\AcademicYear::where('school_id', $schoolId)->get();
        $classrooms = \App\Models\Classroom::where('school_id', $schoolId)->get();
        // Teachers: link via user relation, or directly if school_id exists. 
        // Based on model, Teacher has school_id.
        $teachers = \App\Models\Teacher::with('user')->where('school_id', $schoolId)->get();
        
        // Courses: Fetch for modal dropdown (e.g. course for this school)
        $courses = Course::with(['subject', 'classroom', 'teacher.user'])
            ->where('school_id', $schoolId)
            ->get();

        return view('academic.schedule', compact('academicYears', 'classrooms', 'teachers', 'courses'));
    }

    /**
     * Helper to get Current School ID
     */
    private function getSchoolId()
    {
        $user = Auth::user();
        return $user->school_id ?? $user->teacher?->school_id ?? $user->student?->school_id;
    }

    /**
     * Get Schedule Data (JSON)
     */
    public function getSchedule(Request $request)
    {
        $schoolId = $this->getSchoolId();

        if (!$schoolId) {
            return response()->json([]);
        }

        // 1. Fetch Actual Schedules
        $query = ClassSchedule::with(['course.subject', 'course.classroom', 'course.teacher'])
            ->where('school_id', $schoolId);

        // Filter Logic
        $teacherId = $request->input('teacher_id');
        $academicYearId = $request->input('academic_year_id');
        $classroomId = $request->input('classroom_id');
        
        $user = Auth::user();

        // Force filters for specific roles
        if ($user->role === UserRole::GURU && $user->teacher) {
            $teacherId = $user->teacher->id;
        }
        if ($user->role === UserRole::SISWA && $user->student) {
             $classroomId = $user->student->classroom_id; 
        }

        if ($teacherId) {
            $query->whereHas('course', function($q) use ($teacherId, $academicYearId) {
                $q->where('teacher_id', $teacherId);
                if ($academicYearId) {
                    $q->where('academic_year_id', $academicYearId);
                }
            });
        } elseif ($academicYearId && $classroomId) {
            $query->whereHas('course', function($q) use ($academicYearId, $classroomId) {
                $q->where('academic_year_id', $academicYearId)
                  ->where('classroom_id', $classroomId);
            });
        } else {
            return response()->json([]);
        }

        $schedules = $query->get();

        // 2. Fetch Time Settings (Bell Schedule)
        $timeSettings = \App\Models\SchoolTimeSetting::where('school_id', $schoolId)->get();

        $events = [];
        $daysMap = [
            'sunday' => 0, 'monday' => 1, 'tuesday' => 2, 
            'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6,
        ];

        // -- Process Actual Schedules --
        foreach ($schedules as $schedule) {
            $events[] = [
                'id' => $schedule->id,
                'course_id' => $schedule->course_id,
                'title' => $schedule->course->subject->name . ' - ' . $schedule->course->classroom->name,
                'teacher' => $schedule->course->teacher->user->name ?? 'Unknown',
                'daysOfWeek' => [$daysMap[$schedule->day_of_week] ?? null], 
                'startTime' => \Carbon\Carbon::parse($schedule->start_time)->format('H:i'),
                'endTime' => \Carbon\Carbon::parse($schedule->end_time)->format('H:i'),
                'extendedProps' => [
                    'classroom' => $schedule->course->classroom->name,
                    'course_id' => $schedule->course_id,
                    'type' => 'class'
                ],
                'color' => '#3B82F6', // Blue for classes
            ];
        }

        // -- Process Time Settings for Breaks and Empty Slots --
        // We only generate Empty Slots if we are viewing a specific context (Classroom OR Teacher)
        // Breaks are always shown.

        foreach ($timeSettings as $setting) {
             $dayIndex = $daysMap[$setting->day_of_week] ?? null;
             if ($dayIndex === null) continue;

             $startTime = \Carbon\Carbon::parse($setting->start_time)->format('H:i');
             $endTime = \Carbon\Carbon::parse($setting->end_time)->format('H:i');

             // Handle Breaks
             if ($setting->period_number === null) {
                 $events[] = [
                     'id' => 'break-' . $setting->id,
                     'title' => 'ISTIRAHAT',
                     'daysOfWeek' => [$dayIndex],
                     'startTime' => $startTime,
                     'endTime' => $endTime,
                     'display' => 'background',
                     'backgroundColor' => '#ffeb3b', // Yellow background for break
                     'extendedProps' => ['type' => 'break']
                 ];
                 continue; // Done with break
             }

             // Handle Empty Slots
             // If we have a valid filter context (Classroom or Teacher), check if slot is empty
             if ($teacherId || ($academicYearId && $classroomId)) {
                 // Check if any actual schedule overlaps this time slot for this day
                 $isFilled = $schedules->contains(function ($s) use ($setting, $startTime) {
                     // Simple overlap check: Same day AND equal start time (since we align exactly to periods now)
                     // Note: using strict string comparison for H:i
                     return $s->day_of_week === $setting->day_of_week && 
                            \Carbon\Carbon::parse($s->start_time)->format('H:i') === $startTime;
                 });

                 if (!$isFilled) {
                     $events[] = [
                         'id' => 'empty-' . $setting->id,
                         'title' => 'Jam Kosong',
                         'daysOfWeek' => [$dayIndex],
                         'startTime' => $startTime,
                         'endTime' => $endTime,
                         'display' => 'background', // Or 'block' with specific color? User asked for "Data dummy atau warna berbeda"
                         // Let's use a block event to make it clearer it's an empty slot that can be filled?
                         // User said "color distinct". Background events are distinct.
                         // But maybe they want to click it to add? 
                         // Let's use a normal event but gray.
                         'backgroundColor' => '#E5E7EB', // Gray 200
                         'textColor' => '#9CA3AF', // Gray 400
                         'borderColor' => '#D1D5DB',
                         'extendedProps' => [
                             'type' => 'empty',
                             'day_of_week' => $setting->day_of_week,
                             'period_number' => $setting->period_number
                         ]
                     ];
                 }
             }
        }

        return response()->json($events);
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
            'course_id' => 'required|exists:courses,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            // 'start_time' => 'required|date_format:H:i', // Deprecated
            // 'end_time' => 'required|date_format:H:i|after:start_time', // Deprecated
            'start_period' => 'required|integer',
            'end_period' => 'required|integer|gte:start_period',
        ]);

        $course = Course::findOrFail($request->course_id);
        
        // SECURITY CHECK
        if ($course->school_id !== $schoolId) {
            abort(403, 'Unauthorized course.');
        }

        // Fetch Period Times
        $startPeriodObj = \App\Models\SchoolTimeSetting::where('school_id', $schoolId)
            ->where('day_of_week', $request->day_of_week)
            ->where('period_number', $request->start_period)
            ->first();

        $endPeriodObj = \App\Models\SchoolTimeSetting::where('school_id', $schoolId)
            ->where('day_of_week', $request->day_of_week)
            ->where('period_number', $request->end_period)
            ->first();

        if (!$startPeriodObj || !$endPeriodObj) {
            return response()->json(['message' => 'Jam pelajaran tidak ditemukan untuk hari tersebut.'], 422);
        }

        $startTime = $startPeriodObj->start_time;
        $endTime = $endPeriodObj->end_time;

        // Check Conflicts
        $conflict = ClassSchedule::checkConflict(
            $schoolId,
            $request->day_of_week,
            $startTime,
            $endTime,
            $course->teacher_id,
            $course->classroom_id
        );

        if ($conflict['conflict']) {
            return response()->json(['message' => $conflict['message']], 422);
        }

        $schedule = ClassSchedule::create([
            'school_id' => $schoolId,
            'course_id' => $request->course_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return response()->json(['message' => 'Schedule created successfully', 'schedule' => $schedule]);
    }

    public function update(Request $request, ClassSchedule $classSchedule)
    {
        $schoolId = $this->getSchoolId();

        // Simple auth check
        if (!$schoolId || $classSchedule->school_id !== $schoolId) {
             abort(403);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_period' => 'required|integer',
            'end_period' => 'required|integer|gte:start_period',
        ]);

        $course = Course::findOrFail($request->course_id);

        if ($course->school_id !== $schoolId) {
            abort(403, 'Unauthorized course.');
        }

        // Fetch Period Times
        $startPeriodObj = \App\Models\SchoolTimeSetting::where('school_id', $schoolId)
            ->where('day_of_week', $request->day_of_week)
            ->where('period_number', $request->start_period)
            ->first();

        $endPeriodObj = \App\Models\SchoolTimeSetting::where('school_id', $schoolId)
            ->where('day_of_week', $request->day_of_week)
            ->where('period_number', $request->end_period)
            ->first();

        if (!$startPeriodObj || !$endPeriodObj) {
            return response()->json(['message' => 'Jam pelajaran tidak ditemukan untuk hari tersebut.'], 422);
        }

        $startTime = $startPeriodObj->start_time;
        $endTime = $endPeriodObj->end_time;

        // Check Conflicts
        $conflict = ClassSchedule::checkConflict(
            $classSchedule->school_id,
            $request->day_of_week,
            $startTime,
            $endTime,
            $course->teacher_id,
            $course->classroom_id,
            $classSchedule->id
        );

        if ($conflict['conflict']) {
            return response()->json(['message' => $conflict['message']], 422);
        }

        $classSchedule->update([
            'course_id' => $request->course_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        return response()->json(['message' => 'Schedule updated successfully', 'schedule' => $classSchedule]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSchedule $classSchedule)
    {
         $schoolId = $this->getSchoolId();

         if (!$schoolId || $classSchedule->school_id !== $schoolId) {
             abort(403);
         }
         
         $classSchedule->delete();
         return response()->json(['message' => 'Schedule deleted successfully']);
    }
}
