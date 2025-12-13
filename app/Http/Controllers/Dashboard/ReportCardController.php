<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ReportCard;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;
        
        // Group by Academic Year
        $reportCards = ReportCard::with(['subject', 'academicYear'])
            ->where('student_id', $student->id)
            ->get()
            ->groupBy('academic_year_id');

        $academicYears = AcademicYear::whereIn('id', $reportCards->keys())->get();

        return view('pages.student.report-card.index', compact('reportCards', 'academicYears'));
    }

    public function print($id)
    {
        // Feature to print PDF (Optional for now logic placeholder)
        // $reportCard = ReportCard::findOrFail($id);
        // ...
    }
}
