<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
             return redirect()->route('dashboard.index')->with('error', 'Data siswa tidak ditemukan.');
        }

        $classroom = $student->classroom;
        $courses = $classroom ? $classroom->courses()->with(['teacher.user', 'subject'])->get() : collect();

        // 1. Course Count
        $courseCount = $courses->count();

        // 2. Pending Assignments Count
        // Get all assignment IDs from student's courses
        $assignmentIds = \App\Models\Assignment::whereIn('course_id', $courses->pluck('id'))
                            ->pluck('id');
        
        // Count submitted
        $submittedCount = \App\Models\AssignmentSubmission::where('student_id', $student->id)
                            ->whereIn('assignment_id', $assignmentIds)
                            ->count();
        
        $totalAssignments = \App\Models\Assignment::whereIn('course_id', $courses->pluck('id'))->count();
        $pendingTasksCount = $totalAssignments - $submittedCount;

        // 3. Average Score (Mockup logic or real if Score table exists properly linked)
        // Ignoring for now or using simple average from ExamAttempts + AssignmentSubmissions if needed. 
        // For MVF, let's use 0 or placeholder if no data.
        $avgScore = 0; // To be implemented with detailed scoring logic

        // 4. Attendance Percentage
        $totalAttendanceSessions = \App\Models\Attendance::where('student_id', $student->id)->count();
        $presentSessions = \App\Models\Attendance::where('student_id', $student->id)
                            ->where('status', 'present')
                            ->count();
        $attendancePercentage = $totalAttendanceSessions > 0 ? round(($presentSessions / $totalAttendanceSessions) * 100) : 0;

        // 5. Today's Schedule
        $today = strtolower(now()->format('l')); // e.g. "friday"
        // Map English day to Indo if using Enum or standard storage. 
        // Assuming Enum is standard English or handled elsewhere. Let's check Schedule model later.
        // For now, let's assume standard 'monday', 'tuesday'...
        
        $todaysClasses = \App\Models\ClassSchedule::whereIn('course_id', $courses->pluck('id'))
                            ->where('day_of_week', $today)
                            ->with(['course.subject', 'course.teacher.user'])
                            ->orderBy('start_time')
                            ->get();

        // 6. Upcoming Tasks (Top 3)
        $upcomingTasks = \App\Models\Assignment::whereIn('course_id', $courses->pluck('id'))
                            ->where('due_date', '>=', now())
                            ->whereDoesntHave('submissions', function($q) use ($student) {
                                $q->where('student_id', $student->id);
                            })
                            ->orderBy('due_date', 'asc')
                            ->take(3)
                            ->with('course.subject')
                            ->get();

        // 7. Upcoming Exams (Top 3)
        $upcomingExams = \App\Models\Exam::whereIn('course_id', $courses->pluck('id'))
                            ->where('is_published', true)
                            ->where('end_time', '>=', now())
                            ->whereDoesntHave('attempts', function($q) use ($student) {
                                $q->where('student_id', $student->id)
                                  ->whereNotNull('finished_at'); // Only show if not fully finished
                            })
                            ->orderBy('start_time', 'asc')
                            ->take(3)
                            ->with('course.subject')
                            ->get();

        return view('pages.student.dashboard', compact(
            'student', 
            'courseCount', 
            'pendingTasksCount', 
            'avgScore', 
            'attendancePercentage',
            'todaysClasses',
            'upcomingTasks',
            'upcomingExams'
        ));
    }

    public function courses()
    {
        $student = auth()->user()->student;
        $classroom = $student->classroom;
        // Eager load progress if possible, or calculate in view/service
        $courses = $classroom ? $classroom->courses()->with(['subject', 'teacher.user'])->get() : collect();

        return view('pages.student.courses.index', compact('courses'));
    }

    public function show($id)
    {
        $student = auth()->user()->student;
        $course = \App\Models\Course::with(['subject', 'teacher.user', 'materials', 'assignments.submissions' => function($q) use ($student) {
            $q->where('student_id', $student->id);
        }, 'exams' => function($q) use ($student) {
            $q->where('is_published', true)
              ->orderBy('start_time')
              ->withCount('questions')
              ->with(['attempts' => function($q2) use ($student) {
                  $q2->where('student_id', $student->id);
              }]);
        }])->findOrFail($id);

        // Security check: confirm student is in the class of this course
        if ($course->classroom_id !== $student->classroom_id) {
            abort(403);
        }

        // Material Completion Progress
        $totalMaterials = $course->materials->count();
        $completedMaterials = \App\Models\CourseMaterialCompletion::where('student_id', $student->id)
                                ->whereIn('course_material_id', $course->materials->pluck('id'))
                                ->count();
        $materialProgress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

        // Assignment Progress
        $totalAssignments = $course->assignments->count();
        $submittedAssignments = $course->assignments->filter(function($a) {
            return $a->submissions->isNotEmpty();
        })->count();
        $assignmentProgress = $totalAssignments > 0 ? round(($submittedAssignments / $totalAssignments) * 100) : 0;

        // Attendance stats for this course
        $totalAttendance = \App\Models\Attendance::where('course_id', $course->id)
                            ->where('student_id', $student->id)
                            ->count();
        $presentAttendance = \App\Models\Attendance::where('course_id', $course->id)
                            ->where('student_id', $student->id)
                            ->where('status', 'present')
                            ->count();
        $attendanceProgress = $totalAttendance > 0 ? round(($presentAttendance / $totalAttendance) * 100) : 0;

        return view('pages.student.courses.show', compact(
            'course', 
            'materialProgress', 
            'assignmentProgress', 
            'attendanceProgress',
            'student'
        ));
    }

    public function markMaterialComplete(Request $request, $id)
    {
        $student = auth()->user()->student;
        
        \App\Models\CourseMaterialCompletion::firstOrCreate(
            [
                'student_id' => $student->id,
                'course_material_id' => $id
            ],
            [
                'completed_at' => now()
            ]
        );

        return back()->with('success', 'Materi ditandai selesai.');
    }

    public function schedule()
    {
        $student = auth()->user()->student;
        $classroom = $student->classroom;
        
        // Fetch all schedules
        $schedules = \App\Models\ClassSchedule::whereIn('course_id', $classroom->courses->pluck('id'))
                        ->with(['course.subject', 'course.teacher.user'])
                        ->get();

        return view('pages.student.schedule.index', compact('schedules'));
    }

    public function assignments()
    {
        $student = auth()->user()->student;
        $classroom = $student->classroom;
        
        $courses = $classroom->courses;
        
        $assignments = \App\Models\Assignment::whereIn('course_id', $courses->pluck('id'))
                        ->with(['course.subject', 'submissions' => function($q) use ($student) {
                            $q->where('student_id', $student->id);
                        }])
                        ->orderBy('due_date')
                        ->get();

        return view('pages.student.assignments.index', compact('assignments'));
    }

    public function submitAssignment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,zip,jpeg,png|max:10240', // 10MB max
        ]);

        $student = auth()->user()->student;
        
        // Ensure assignment exists and belongs to a course the student is taking
        $assignment = \App\Models\Assignment::findOrFail($id);
        
        // Security check (optional but recommended): is student enrolled in this course?
        if ($assignment->course->classroom_id !== $student->classroom_id) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $path = $request->file('file')->store('assignments', 'public');

        \App\Models\AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $student->id
            ],
            [
                'file_path' => $path,
                'submitted_at' => now(),
            ]
        );

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }

    public function showAssignment($id)
    {
        $student = auth()->user()->student;
        $assignment = \App\Models\Assignment::with(['course.subject', 'course.teacher.user'])->findOrFail($id);

        if ($assignment->course->classroom_id !== $student->classroom_id) {
             abort(403);
        }

        $submission = \App\Models\AssignmentSubmission::where('assignment_id', $id)
                        ->where('student_id', $student->id)
                        ->first();

        return view('pages.student.assignments.show', compact('assignment', 'submission'));
    }

    public function profile()
    {
        $student = auth()->user()->student;
        return view('pages.student.profile.index', compact('student'));
    }
}
