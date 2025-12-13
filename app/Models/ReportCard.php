<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    protected $table = 'report_cards';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'academic_year_id',
        'formative_score',
        'mid_term_score',
        'final_term_score',
        'final_grade',
        'predicate',
        'comments'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
