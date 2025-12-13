<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'school_id',
        'course_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check for scheduling conflicts.
     *
     * @param int $schoolId
     * @param string $day
     * @param string $startTime
     * @param string $endTime
     * @param int|null $teacherId
     * @param int|null $classroomId
     * @param int|null $ignoreScheduleId
     * @return array
     */
    public static function checkConflict($schoolId, $day, $startTime, $endTime, $teacherId = null, $classroomId = null, $ignoreScheduleId = null)
    {
        $query = self::query()
            ->where('school_id', $schoolId)
            ->where('day_of_week', $day)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                       ->where('end_time', '>', $startTime);
                });
            });

        if ($ignoreScheduleId) {
            $query->where('id', '!=', $ignoreScheduleId);
        }

        // Check Teacher Conflict
        if ($teacherId) {
            $teacherConflict = (clone $query)->whereHas('course', function ($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId);
            })->exists();

            if ($teacherConflict) {
                return ['conflict' => true, 'type' => 'teacher', 'message' => 'Teacher is already teaching at this time.'];
            }
        }

        // Check Classroom Conflict
        if ($classroomId) {
            $classroomConflict = (clone $query)->whereHas('course', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            })->exists();

            if ($classroomConflict) {
                return ['conflict' => true, 'type' => 'classroom', 'message' => 'Classroom is already occupied at this time.'];
            }
        }

        return ['conflict' => false];
    }
}
