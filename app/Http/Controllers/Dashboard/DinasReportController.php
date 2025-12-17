<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SchoolReport;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

class DinasReportController extends Controller
{
    public function incoming(Request $request)
    {
        $query = SchoolReport::with(['school', 'uploader'])->where('status', 'submitted');

        // Filters
        if ($request->filled('school_level')) {
            $query->whereHas('school', function ($q) use ($request) {
                $q->where('education_level', $request->school_level);
            });
        }

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereYear('report_period', $request->year)
                ->whereMonth('report_period', $request->month);
        }

        $reports = $query->latest()->paginate(10);
        return view('dashboard.dinas.reports.incoming', compact('reports'));
    }

    public function archive(Request $request)
    {
        $query = SchoolReport::with(['school', 'uploader'])->where('status', 'accepted');

        // Reuse Filter Logic could be extracted
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $reports = $query->latest()->paginate(10);
        return view('dashboard.dinas.reports.archive', compact('reports'));
    }

    public function show($id)
    {
        $report = SchoolReport::with(['school', 'uploader', 'reviewer', 'histories'])->findOrFail($id);
        return view('dashboard.dinas.reports.show', compact('report'));
    }

    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,revision_needed,rejected',
            'dinas_feedback' => 'nullable|string'
        ]);

        $report = SchoolReport::findOrFail($id);

        $report->update([
            'status' => $request->status,
            'dinas_feedback' => $request->dinas_feedback,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('dinas.reports.incoming')
            ->with('success', 'Laporan berhasil divalidasi.');
    }
}
