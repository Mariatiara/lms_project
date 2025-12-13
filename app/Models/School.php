<?php

namespace App\Models;

use App\Enums\SchoolStatus;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $fillable = [
        'npsn',
        'name',
        'education_level',
        'ownership_status',
        'address',
        'district',
        'village',
        'verification_doc',
        'logo',
        'status',
        'email',
        'phone',
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function academicYears()
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function classSchedules()
    {
        return $this->hasMany(ClassSchedule::class);
    }

    public function admin()
    {
        return $this->hasOne(User::class)->where('role', UserRole::ADMIN_SEKOLAH);
    }

    protected $casts = [
        'status' => SchoolStatus::class,
    ];
}
