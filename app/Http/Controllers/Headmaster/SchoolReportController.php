<?php

namespace App\Http\Controllers\Headmaster;

use App\Http\Controllers\Controller;
use App\Models\SchoolReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SchoolReportController extends Controller
{
    public function index()
    {
        $reports = SchoolReport::where('school_id', Auth::user()->school_id)
            ->latest()
            ->paginate(10);

        return view('pages.headmaster.reports.index', compact('reports'));
    }

    public function create()
    {
        return view('pages.headmaster.reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:Bulanan,Semester,Tahunan,Keuangan,Insidental',
            'report_period' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        $path = $request->file('file')->store('school-reports', 'public');

        SchoolReport::create([
            'school_id' => Auth::user()->school_id,
            'uploaded_by' => Auth::id(),
            'title' => $request->title,
            'report_type' => $request->report_type,
            'report_period' => $request->report_period,
            'description' => $request->description,
            'status' => 'submitted',
            'file_path' => $path,
        ]);

        return redirect()->route('school-reports.index')->with('success', 'Laporan berhasil diunggah.');
    }

    public function show(SchoolReport $schoolReport)
    {
        if ($schoolReport->school_id !== Auth::user()->school_id) {
            abort(403);
        }
        return view('pages.headmaster.reports.show', compact('schoolReport'));
    }

    public function edit(SchoolReport $schoolReport)
    {
        if ($schoolReport->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        if (!in_array($schoolReport->status, ['submitted', 'revision_needed', 'rejected'])) {
            return redirect()->route('school-reports.show', $schoolReport->id)
                ->with('error', 'Laporan dengan status ini tidak dapat diedit.');
        }

        return view('pages.headmaster.reports.edit', compact('schoolReport'));
    }

    public function update(Request $request, SchoolReport $schoolReport)
    {
        if ($schoolReport->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:Bulanan,Semester,Tahunan,Keuangan,Insidental',
            'report_period' => 'required|date',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        // Archive if file changes or status is being reset
        $isResubmission = in_array($schoolReport->status, ['revision_needed', 'rejected']);

        if ($request->hasFile('file') || $isResubmission) {
            // Create History
            \App\Models\SchoolReportHistory::create([
                'school_report_id' => $schoolReport->id,
                'file_path' => $schoolReport->file_path,
                'dinas_feedback' => $schoolReport->dinas_feedback,
                'status' => $schoolReport->status,
            ]);
        }

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('school-reports', 'public');
            $schoolReport->file_path = $path;
        }

        $schoolReport->title = $request->title;
        $schoolReport->report_type = $request->report_type;
        $schoolReport->report_period = $request->report_period;
        $schoolReport->description = $request->description;

        if ($isResubmission) {
            $schoolReport->status = 'submitted';
            $schoolReport->dinas_feedback = null; // Reset feedback for new submission
        }

        $schoolReport->save();

        return redirect()->route('school-reports.show', $schoolReport->id)->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(SchoolReport $schoolReport)
    {
        if ($schoolReport->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        // Optional: Delete file from storage
        if ($schoolReport->file_path) {
            Storage::disk('public')->delete($schoolReport->file_path);
        }

        $schoolReport->delete();
        return redirect()->route('school-reports.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
