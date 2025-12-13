<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Course;

class TeacherExamController extends Controller
{
    public function index($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Auth check
        if($course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $exams = $course->exams()->latest()->get();

        return view('pages.guru.exams.index', compact('course', 'exams'));
    }

    public function create($courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Auth check
        if($course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        return view('pages.guru.exams.create', compact('course'));
    }

    public function store(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        
        // Auth check
        if($course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', new \Illuminate\Validation\Rules\Enum(\App\Enums\ExamCategory::class)],
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $exam = Exam::create([
            'course_id' => $course->id,
            'title' => $request->title,
            'category' => $request->category,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('teacher.exams.index', $course->id)->with('success', 'Ujian berhasil dibuat.');
    }

    public function edit($id)
    {
        $exam = Exam::with('course')->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        return view('pages.guru.exams.edit', compact('exam'));
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::with('course')->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => ['required', new \Illuminate\Validation\Rules\Enum(\App\Enums\ExamCategory::class)],
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $exam->update([
            'title' => $request->title,
            'category' => $request->category,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'is_published' => $request->has('is_published'),
        ]);

        return redirect()->route('teacher.exams.index', $exam->course_id)->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $exam = Exam::with('course')->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $exam->delete();

        return back()->with('success', 'Ujian berhasil dihapus.');
    }

    // Question Management
    public function questions($id)
    {
        $exam = Exam::with(['course', 'questions'])->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        return view('pages.guru.exams.questions', compact('exam'));
    }

    public function storeQuestion(Request $request, $id)
    {
        $exam = Exam::with('course')->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $request->validate([
            'question_type' => 'required|string',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:0',
            // Options required if objective
            'options' => 'nullable|array',
            'correct_answer' => 'nullable|string',
        ]);

        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question_type' => $request->question_type,
            'question_text' => $request->question_text,
            'points' => $request->points,
            'options' => $request->options,
            'correct_answer' => $request->correct_answer,
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan.');
    }

    public function destroyQuestion($id)
    {
        $question = ExamQuestion::with('exam.course')->findOrFail($id);
        
        // Auth check
        if($question->exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $question->delete();

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    // Grading & Results
    public function examResults($id)
    {
        $exam = Exam::with(['course', 'attempts.student.user'])->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        return view('pages.guru.exams.results', compact('exam'));
    }

    public function reviewAttempt($id, $attemptId)
    {
        $exam = Exam::with(['course.subject', 'questions'])->findOrFail($id);
        
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $attempt = \App\Models\ExamAttempt::with(['student.user', 'answers'])->findOrFail($attemptId);

        return view('pages.guru.exams.review', compact('exam', 'attempt'));
    }

    public function storeGrade(Request $request, $id, $attemptId)
    {
        $exam = Exam::with(['course', 'questions'])->findOrFail($id);
        // Auth check
        if($exam->course->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $attempt = \App\Models\ExamAttempt::with('answers')->findOrFail($attemptId);
        $scores = $request->input('scores', []); // [question_id => score]

        $totalScore = 0;

        // Process manual scores
        foreach($scores as $questionId => $score) {
            \App\Models\ExamAnswer::updateOrCreate(
                [
                    'exam_attempt_id' => $attempt->id,
                    'exam_question_id' => $questionId,
                ],
                [
                    'score' => $score
                ]
            );
        }

        // Recalculate Total
        // We need to re-fetch answers to get updated scores + existing auto-graded scores
        $attempt->load('answers');
        $obtainedPoints = $attempt->answers->sum('score');
        
        $maxPoints = $exam->questions->sum('points');
        $finalScore = ($maxPoints > 0) ? ($obtainedPoints / $maxPoints) * 100 : 0;

        $attempt->update([
            'total_score' => $finalScore
        ]);

        return redirect()->route('teacher.exams.results', $exam->id)->with('success', 'Nilai berhasil diperbarui.');
    }
}
