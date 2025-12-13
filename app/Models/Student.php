<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Enums\StudentStatus;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'school_id',
        'classroom_id',
        'nama',
        'nis',
        'alamat',
        'telepon',
        'status',
    ];

    protected $casts = [
        'status' => StudentStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
