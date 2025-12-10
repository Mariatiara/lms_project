<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SchoolAdminController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $schoolId = $user->school_id;
        $school = $user->school;

        $totalStudents = \App\Models\Student::where('school_id', $schoolId)->count();
        $totalTeachers = \App\Models\Teacher::where('school_id', $schoolId)->count();
        $totalClasses = \App\Models\Classroom::where('school_id', $schoolId)->count();
        $activeAcademicYear = \App\Models\AcademicYear::where('school_id', $schoolId)
                                ->where('is_active', true)
                                ->first();

        // 1. Student Data Completeness (Example: check mandatory fields)
        $studentsWithGenerics = \App\Models\Student::where('school_id', $schoolId)
            ->where(function($q) {
                $q->whereNull('nis')
                  ->orWhereNull('alamat')
                  ->orWhereNull('telepon');
            })->count();
        
        $studentDataProgress = $totalStudents > 0 
            ? round((($totalStudents - $studentsWithGenerics) / $totalStudents) * 100) 
            : 0;

        // 2. Teacher Data Completeness
        $teachersWithGenerics = \App\Models\Teacher::where('school_id', $schoolId)
            ->where(function($q) {
                $q->whereNull('nip')
                  ->orWhereNull('specialization');
            })->count();

        $teacherDataProgress = $totalTeachers > 0
            ? round((($totalTeachers - $teachersWithGenerics) / $totalTeachers) * 100)
            : 0;
            
        // 3. School Data Completeness
        $schoolFields = ['logo', 'address', 'email', 'phone'];
        $filledSchoolFields = 0;
        foreach($schoolFields as $field) {
            if (!empty($school->$field)) $filledSchoolFields++;
        }
        $schoolDataProgress = round(($filledSchoolFields / count($schoolFields)) * 100);

        // 4. Schedule Status
        $scheduleCreated = false;
        if ($activeAcademicYear) {
            // Check if any schedule exists for the active academic year (via courses -> classSchedules)
            // Or simpler: check if any ClassSchedule exists for this school (approximate if year not linked directly)
            // Ideally, ClassSchedule should link to Course which links to AcademicYear.
            // Based on models: ClassSchedule -> Course -> AcademicYear
            $scheduleCreated = \App\Models\ClassSchedule::where('school_id', $schoolId)
                ->whereHas('course', function($q) use ($activeAcademicYear) {
                    $q->where('academic_year_id', $activeAcademicYear->id);
                })->exists();
        }

        return view('dashboard.school.admin', compact(
            'totalStudents', 
            'totalTeachers', 
            'totalClasses', 
            'activeAcademicYear',
            'studentDataProgress',
            'teacherDataProgress',
            'schoolDataProgress',
            'scheduleCreated'
        ));
    }
}
