<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAnswer;
use App\Models\ExamQuestion;
use App\Models\Course;

class StudentExamController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        $classroomId = $student->classroom_id;

        // Get exams for courses in student's classroom
        $exams = Exam::whereHas('course', function($q) use ($classroomId) {
            $q->where('classroom_id', $classroomId);
        })
        ->where('is_published', true)
        ->latest('start_time')
        ->get();

        return view('pages.student.exams.index', compact('exams'));
    }

    public function show($id)
    {
        $exam = Exam::with('course.subject')->where('is_published', true)->findOrFail($id);
        $student = auth()->user()->student;

        // Security: Check if student belongs to course
        if($exam->course->classroom_id !== $student->classroom_id) {
            abort(403);
        }

        // Check if already attempted
        $existingAttempt = ExamAttempt::where('exam_id', $id)
                            ->where('student_id', $student->id)
                            ->first();

        return view('pages.student.exams.show', compact('exam', 'existingAttempt'));
    }

    public function start(Request $request, $id)
    {
        $exam = Exam::where('is_published', true)->findOrFail($id);
        $student = auth()->user()->student;

        if($exam->course->classroom_id !== $student->classroom_id) {
            abort(403);
        }

        // Check timing
        $now = now();
        if($now->lt($exam->start_time) || $now->gt($exam->end_time)) {
             return back()->with('error', 'Ujian tidak tersedia saat ini.');
        }

        // Create or get attempt
        $attempt = ExamAttempt::firstOrCreate(
            [
                'exam_id' => $exam->id,
                'student_id' => $student->id
            ],
            [
                'started_at' => $now,
            ]
        );

        if($attempt->finished_at) {
             return redirect()->route('student.exams.result', $exam->id);
        }

        return redirect()->route('student.exams.take', $exam->id);
    }

    public function take($id)
    {
        $exam = Exam::with(['questions', 'course.subject'])->findOrFail($id);
        $student = auth()->user()->student;
        
        $attempt = ExamAttempt::where('exam_id', $id)
                    ->where('student_id', $student->id)
                    ->firstOrFail();

        if($attempt->finished_at) {
             return redirect()->route('student.exams.result', $exam->id);
        }
        
         // Check if time run out independently
        $timeSpent = now()->diffInMinutes($attempt->started_at);
        if($timeSpent > $exam->duration_minutes) {
             // Force finish
             $attempt->update(['finished_at' => now()]);
             return redirect()->route('student.exams.result', $exam->id)->with('info', 'Waktu habis.');
        }

        return view('pages.student.exams.take', compact('exam', 'attempt'));
    }

    public function submit(Request $request, $id)
    {
        $exam = Exam::with('questions')->findOrFail($id);
        $student = auth()->user()->student;
        
        $attempt = ExamAttempt::where('exam_id', $id)
                    ->where('student_id', $student->id)
                    ->firstOrFail();

        if($attempt->finished_at) {
           return redirect()->route('student.exams.result', $exam->id);
        }

        $answers = $request->input('answers'); // [question_id => answer]
        $totalScore = 0;

        foreach($exam->questions as $question) {
            $submittedAnswer = $answers[$question->id] ?? null;
            $score = 0;
            
            // Auto Grading
            if($question->question_type === ExamQuestion::TYPE_MULTIPLE_CHOICE || $question->question_type === ExamQuestion::TYPE_TRUE_FALSE) {
                if($submittedAnswer == $question->correct_answer) {
                    $score = $question->points;
                }
            }
            // Add other types logic here (Multiple Answer, etc)

            ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'exam_question_id' => $question->id,
                ],
                [
                    'answer' => is_array($submittedAnswer) ? json_encode($submittedAnswer) : $submittedAnswer,
                    'score' => $score // 0 for essays initially
                ]
            );

            $totalScore += $score;
        }

        // Normalize Score to 0-100
        $maxPoints = $exam->questions->sum('points');
        $finalScore = ($maxPoints > 0) ? ($totalScore / $maxPoints) * 100 : 0;

        $attempt->update([
            'finished_at' => now(),
            'total_score' => $finalScore
        ]);

        return redirect()->route('student.exams.result', $exam->id)->with('success', 'Ujian selesai.');
    }

    public function result($id)
    {
        // Eager load questions for detailed view
        $exam = Exam::with(['course.subject', 'questions'])->findOrFail($id);
        $student = auth()->user()->student;
        
        $attempt = ExamAttempt::where('exam_id', $id)
                    ->where('student_id', $student->id)
                    ->with('answers')
                    ->firstOrFail();

        return view('pages.student.exams.result', compact('exam', 'attempt'));
    }
}
