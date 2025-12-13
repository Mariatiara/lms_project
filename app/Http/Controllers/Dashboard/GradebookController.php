<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\GradeWeights;
use App\Models\ReportCard;
use App\Models\Exam;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;
use App\Enums\ExamCategory;

class GradebookController extends Controller
{
    public function index($courseId)
    {
        $course = Course::with(['classroom.students.user', 'subject', 'school'])->findOrFail($courseId);
        $teacher = auth()->user()->teacher;

        // Security check
        if ($course->teacher_id !== $teacher->id) {
            abort(403);
        }

        // Get weights
        $weights = GradeWeights::where('school_id', $course->school_id)->get()->keyBy('category');
        
        // Defaults if not set (2:1:1)
        $wFormative = $weights['daily']->weight ?? 2;
        $wMid = $weights['mid_term']->weight ?? 1;
        $wFinal = $weights['final_term']->weight ?? 1;
        $totalWeight = $wFormative + $wMid + $wFinal;

        $students = $course->classroom->students;
        $gradebook = [];

        foreach ($students as $student) {
            // 1. Formative Score (Assignments + Daily Exams)
            // Get all assignment submissions matching this course's assignments
            $assignmentScore = DB::table('assignment_submissions')
                ->join('assignments', 'assignment_submissions.assignment_id', '=', 'assignments.id')
                ->where('assignments.course_id', $courseId)
                ->where('assignment_submissions.student_id', $student->id)
                ->avg('assignment_submissions.score');

            // Get all daily exams
            $dailyExamScore = DB::table('exam_attempts')
                ->join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exams.course_id', $courseId)
                ->where('exams.category', 'daily')
                ->where('exam_attempts.student_id', $student->id)
                ->avg('exam_attempts.total_score');

            // Average of both (handle nulls)
            $components = [];
            if ($assignmentScore !== null) $components[] = $assignmentScore;
            if ($dailyExamScore !== null) $components[] = $dailyExamScore;
            
            $formativeScore = count($components) > 0 ? array_sum($components) / count($components) : 0;

            // 2. Mid Term Score
            $midTermScore = DB::table('exam_attempts')
                ->join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exams.course_id', $courseId)
                ->where('exams.category', 'mid_term')
                ->where('exam_attempts.student_id', $student->id)
                ->max('exam_attempts.total_score') ?? 0;

            // 3. Final Term Score
            $finalTermScore = DB::table('exam_attempts')
                ->join('exams', 'exam_attempts.exam_id', '=', 'exams.id')
                ->where('exams.course_id', $courseId)
                ->where('exams.category', 'final_term')
                ->where('exam_attempts.student_id', $student->id)
                ->max('exam_attempts.total_score') ?? 0;

            // 4. Calculate Final
            $finalGrade = 0;
            if ($totalWeight > 0) {
                $finalGrade = (($formativeScore * $wFormative) + ($midTermScore * $wMid) + ($finalTermScore * $wFinal)) / $totalWeight;
            }

            // 5. Predicate
            $predicate = 'E';
            if ($finalGrade >= 90) $predicate = 'A';
            elseif ($finalGrade >= 80) $predicate = 'B';
            elseif ($finalGrade >= 70) $predicate = 'C';
            elseif ($finalGrade >= 60) $predicate = 'D';

            // Check if already finalized
            $existingReport = ReportCard::where('student_id', $student->id)
                ->where('subject_id', $course->subject_id)
                ->where('academic_year_id', $course->academic_year_id)
                ->first();

            $gradebook[] = [
                'student' => $student,
                'formative' => round($formativeScore, 2),
                'mid_term' => round($midTermScore, 2),
                'final_term' => round($finalTermScore, 2),
                'final_grade' => round($finalGrade, 2),
                'predicate' => $predicate,
                'is_finalized' => (bool) $existingReport
            ];
        }

        return view('pages.guru.gradebook.index', compact('course', 'gradebook'));
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Batch insert/update for all students in request
        $grades = $request->input('grades'); // Array of student data

        if ($grades) {
            foreach($grades as $studentId => $data) {
                 ReportCard::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $course->subject_id,
                        'academic_year_id' => $course->academic_year_id,
                    ],
                    [
                        'teacher_id' => auth()->user()->teacher->id,
                        'formative_score' => $data['formative'],
                        'mid_term_score' => $data['mid_term'],
                        'final_term_score' => $data['final_term'],
                        'final_grade' => $data['final_grade'],
                        'predicate' => $data['predicate'],
                        'comments' => $data['comments'] ?? ''
                    ]
                 );
            }
        }

        return back()->with('success', 'Nilai berhasil disimpan ke Rapor!');
    }
}
