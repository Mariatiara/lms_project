<?php

namespace App\Http\Controllers\Dashboard\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GradeWeightController extends Controller
{
    public function index()
    {
        $schoolId = \Illuminate\Support\Facades\Auth::user()->school_id;
        $weights = \App\Models\GradeWeights::where('school_id', $schoolId)->get()->keyBy('category');
        
        return view('pages.school.admin.grade-weights.index', compact('weights'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'daily' => 'required|numeric|min:0',
            'mid_term' => 'required|numeric|min:0',
            'final_term' => 'required|numeric|min:0',
        ]);

        $schoolId = \Illuminate\Support\Facades\Auth::user()->school_id;
        $categories = ['daily', 'mid_term', 'final_term'];

        foreach ($categories as $category) {
            \App\Models\GradeWeights::updateOrCreate(
                ['school_id' => $schoolId, 'category' => $category],
                ['weight' => $request->input($category)]
            );
        }

        return redirect()->back()->with('success', 'Bobot nilai berhasil diperbarui!');
    }
}
