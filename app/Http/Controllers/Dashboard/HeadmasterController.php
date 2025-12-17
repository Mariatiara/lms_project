<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\SchoolReport;
use App\Models\SchoolEvent;
use Illuminate\Support\Facades\Auth;

class HeadmasterController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;

        $stats = [
            'student_count' => Student::where('school_id', $schoolId)->count(),
            'teacher_count' => Teacher::where('school_id', $schoolId)->count(),
            'classroom_count' => Classroom::where('school_id', $schoolId)->count(),
        ];

        $latestReports = SchoolReport::where('school_id', $schoolId)
            ->latest()
            ->take(5)
            ->get();

        $upcomingEvents = SchoolEvent::where('school_id', $schoolId)
            ->whereDate('start_date', '>=', now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('pages.dashboard.headmaster', compact('stats', 'latestReports', 'upcomingEvents'));
    }
}
