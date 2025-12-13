<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMaterialCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_material_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function courseMaterial()
    {
        return $this->belongsTo(CourseMaterial::class);
    }
}
