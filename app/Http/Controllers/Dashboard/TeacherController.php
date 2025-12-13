<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index()
    {
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        
        // 1. Total Courses in Active Academic Year
        $activeAcademicYearId = \App\Models\AcademicYear::where('school_id', $teacher->user->school_id)
                                    ->where('is_active', true)
                                    ->value('id');

        $activeCoursesQuery = \App\Models\Course::where('teacher_id', $teacher->id)
                                ->where('academic_year_id', $activeAcademicYearId);
        
        $totalActiveCourses = $activeCoursesQuery->count();

        // 2. Total Unique Students in Active Classes
        $activeClassroomIds = $activeCoursesQuery->pluck('classroom_id')->unique();
        $totalStudents = \App\Models\Student::whereIn('classroom_id', $activeClassroomIds)->count();

        // 3. Active Assignments (Due date > Now) across all teacher's courses (all years or just active? Assuming all applicable)
        // Usually dashboard focuses on active workload, so let's stick to active courses assignments or all future assignments.
        // Let's filter by assignments in the teacher's courses where due_date is in future.
        $activeAssignmentsCount = \App\Models\Assignment::whereHas('course', function($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->where('due_date', '>', now())->count();

        // 4. Average Attendance (Percentage of 'present' status)
        // Formula: (Total 'present' / Total attendance records) * 100
        $attendanceQuery = \App\Models\Attendance::whereHas('course', function($q) use ($teacher, $activeAcademicYearId) {
            $q->where('teacher_id', $teacher->id)
              ->where('academic_year_id', $activeAcademicYearId); // Limit to active year for relevance
        });
        
        $totalAttendanceRecords = $attendanceQuery->count();
        $totalPresent = $attendanceQuery->where('status', 'present')->count();
        $averageAttendance = $totalAttendanceRecords > 0 ? round(($totalPresent / $totalAttendanceRecords) * 100, 1) : 0;

        // 5. Chart Data: Students per Class (Top 5 largest classes? or all active?)
        $studentsPerClass = $activeCoursesQuery->with('classroom')
                            ->get()
                            ->map(function($course) {
                                return [
                                    'class_name' => $course->classroom->name ?? 'Unknown',
                                    'student_count' => $course->classroom->students()->count()
                                ];
                            })->values();

        // 6. 3 Active Courses (Card Model)
        $activeCourses = $activeCoursesQuery->with(['classroom' => function($q) {
                                $q->withCount('students');
                            }, 'subject'])
                            ->withCount(['materials'])
                            ->latest()
                            ->take(3)
                            ->get();

        // 7. Today's Schedule
        $today = strtolower(now()->format('l'));
        $todaysClasses = \App\Models\ClassSchedule::whereHas('course', function($q) use ($teacher, $activeAcademicYearId) {
            $q->where('teacher_id', $teacher->id)
              ->where('academic_year_id', $activeAcademicYearId);
        })
        ->where('day_of_week', $today)
        ->with(['course.classroom', 'course.subject'])
        ->orderBy('start_time')
        ->get();

        // 7. Assignments Needing Grading (Assignments with submissions where score is null)
        $pendingGrading = \App\Models\Assignment::whereHas('course', function($q) use ($teacher) {
                                    $q->where('teacher_id', $teacher->id);
                                })
                                ->whereHas('submissions', function($q) {
                                    $q->whereNull('score'); // Changed 'grade' to 'score'
                                })
                                ->with(['course.classroom', 'course.subject'])
                                ->withCount(['submissions as pending_count' => function($q) {
                                    $q->whereNull('score');
                                }])
                                ->latest('due_date')
                                ->take(5)
                                ->get();

        return view('dashboard.school.teacher', compact(
            'totalActiveCourses', 
            'totalStudents', 
            'activeAssignmentsCount', 
            'averageAttendance',
            'studentsPerClass',
            'activeCourses',
            'pendingGrading',
            'todaysClasses'
        ));
    }

    public function myCourses()
    {
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        $courses = \App\Models\Course::where('teacher_id', $teacher->id)
                    ->with(['classroom' => function($q) {
                        $q->withCount('students');
                    }, 'subject'])
                    ->withCount(['materials'])
                    ->get();

        return view('pages.guru.courses.index', compact('courses'));
    }

    public function show($id)
    {
        $course = \App\Models\Course::with(['classroom.students.user', 'materials', 'assignments', 'subject'])
                    ->withCount(['materials', 'assignments'])
                    ->findOrFail($id);

        // Security check: Ensure course belongs to teacher
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($course->teacher_id !== $teacher->id) {
            abort(403, 'Unauthorized access to this course');
        }
        
        $totalStudents = $course->classroom->students()->count();
        
        // Calculate total meetings based on unique attendance dates
        $totalMeetings = $course->attendances()->distinct('date')->count('date');

        // Fetch Attendance History
        $rawAttendance = $course->attendances()
            ->select('date', 'status')
            ->get();

        $attendanceHistory = $rawAttendance->groupBy('date')->map(function ($dateGroup) {
            return [
                'date' => $dateGroup->first()->date,
                'total' => $dateGroup->count(),
                'present' => $dateGroup->where('status', 'present')->count(),
                'permit' => $dateGroup->where('status', 'permission')->count(),
                'sick' => $dateGroup->where('status', 'sick')->count(),
                'alpha' => $dateGroup->where('status', 'absent')->count(),
            ];
        })->sortByDesc('date');

        return view('pages.guru.courses.show', compact('course', 'totalStudents', 'totalMeetings', 'attendanceHistory'));
    }

    public function storeAttendance(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array', // [student_id => status]
        ]);

        $course = \App\Models\Course::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        // Map short codes to database enums
        $statusMap = [
            'H' => 'present',
            'I' => 'permission',
            'S' => 'sick',
            'A' => 'absent',
            // Fallback just in case
            'present' => 'present',
            'permission' => 'permission',
            'sick' => 'sick',
            'absent' => 'absent' 
        ];

        foreach ($request->attendance as $studentId => $status) {
            // Get correct enum value or default to absent if unknown
            $dbStatus = $statusMap[$status] ?? 'absent';

            \App\Models\Attendance::updateOrCreate(
                [
                    'course_id' => $course->id,
                    'student_id' => $studentId,
                    'date' => $request->date,
                ],
                [
                    'status' => $dbStatus,
                ]
            );
        }

        return back()->with('success', 'Absensi berhasil disimpan.')->with('active_tab', 'absensi');
    }

    public function getAttendanceByDate($id, $date)
    {
        $course = \App\Models\Course::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $attendances = \App\Models\Attendance::where('course_id', $id)
                        ->where('date', $date)
                        ->get()
                        ->mapWithKeys(function($item) {
                            return [$item->student_id => $item->status];
                        });

        return response()->json($attendances);
    }

    public function storeMaterial(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|max:10240', // 10MB
            'description' => 'nullable|string',
        ]);

        $course = \App\Models\Course::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $file = $request->file('file');
        $path = $file->store('materials/' . $course->id, 'public');

        \App\Models\CourseMaterial::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return back()->with('success', 'Materi berhasil diunggah.');
    }

    public function destroyMaterial($id)
    {
        $material = \App\Models\CourseMaterial::findOrFail($id);
        
         // Auth check via course
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($material->course->teacher_id !== $teacher->id) {
            abort(403);
        }
        
        // Delete file
        if(\Illuminate\Support\Facades\Storage::disk('public')->exists($material->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($material->file_path);
        }
        
        $material->delete();
        
        return back()->with('success', 'Materi berhasil dihapus.');
    }

    public function storeAssignment(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $course = \App\Models\Course::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        \App\Models\Assignment::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Tugas berhasil dibuat.');
    }

    public function updateMaterial(Request $request, $id)
    {
        $material = \App\Models\CourseMaterial::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($material->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:10240', // File is optional on update
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if(\Illuminate\Support\Facades\Storage::disk('public')->exists($material->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($material->file_path);
            }
            
            $file = $request->file('file');
            $path = $file->store('materials/' . $material->course_id, 'public');
            
            $material->file_path = $path;
            $material->file_type = $file->getClientOriginalExtension();
        }

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Materi berhasil diperbarui.');

    }

    public function updateAssignment(Request $request, $id)
    {
        $assignment = \App\Models\Assignment::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($assignment->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ]);

        $assignment->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return back()->with('success', 'Tugas berhasil diperbarui.');
    }

    public function showAssignment($id)
    {
        $assignment = \App\Models\Assignment::with(['course.classroom', 'course.subject'])->findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($assignment->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        $students = $assignment->course->classroom->students()->orderBy('nama')->get();
        $submissions = $assignment->submissions->keyBy('student_id');

        return view('pages.guru.assignments.show', compact('assignment', 'students', 'submissions'));
    }

    public function gradeSubmission(Request $request, $id)
    {
        // $id is Assignment ID, we need student_id from request
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $assignment = \App\Models\Assignment::findOrFail($id);
        
        // Auth check
        $teacher = \Illuminate\Support\Facades\Auth::user()->teacher;
        if($assignment->course->teacher_id !== $teacher->id) {
            abort(403);
        }

        \App\Models\AssignmentSubmission::updateOrCreate(
            [
                'assignment_id' => $assignment->id,
                'student_id' => $request->student_id
            ],
            [
                'score' => $request->score,
                'feedback' => $request->feedback,
                // If grading without submission (e.g. manual offline submission), set submitted_at?
                // Ideally they should have submitted something, but we can allow grading empty submissions if needed.
                // For now, let's assume this updates an existing submission or creates a grade record.
            ]
        );

        return back()->with('success', 'Nilai berhasil disimpan.');
    }
}
