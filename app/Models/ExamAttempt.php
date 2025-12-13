<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'finished_at',
        'total_score',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'total_score' => 'decimal:2',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
