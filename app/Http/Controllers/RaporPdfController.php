<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReportCard;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RaporPdfController extends Controller
{
    public function generate($student_id)
    {
        $student = User::findOrFail($student_id);
        // Group by Semester/Academic Year
        $reportCards = ReportCard::with(['subject', 'academicYear'])
            ->where('student_id', $student_id)
            ->get()
            ->groupBy('academic_year_id');

        $pdf = Pdf::loadView('pages.rapor.pdf', compact('student', 'reportCards'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('Rapor_'.$student->name.'.pdf');
    }
}
