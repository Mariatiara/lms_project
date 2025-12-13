<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'exam_id',
        'question_type',
        'question_text',
        'points',
        'options',
        'correct_answer',
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
    ];
    
    // Helper constants for question types
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_MULTIPLE_ANSWER = 'multiple_answer'; // Checkboxes
    const TYPE_SHORT_ANSWER = 'short_answer';
    const TYPE_ESSAY = 'essay';
    const TYPE_TRUE_FALSE = 'true_false';

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
