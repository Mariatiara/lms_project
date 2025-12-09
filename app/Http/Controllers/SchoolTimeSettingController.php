<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolTimeSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolTimeSettingController extends Controller
{
    public function index()
    {
        $schoolId = $this->getSchoolId();
        if (!$schoolId) abort(403);

        $settings = SchoolTimeSetting::where('school_id', $schoolId)
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        return view('academic.time-settings', compact('settings'));
    }

    public function store(Request $request) 
    {
        $schoolId = $this->getSchoolId();
        if (!$schoolId) abort(403);

        $data = $request->validate([
            'settings' => 'required|array',
            'settings.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'settings.*.period_number' => 'nullable|integer',
            'settings.*.label' => 'required|string',
            'settings.*.start_time' => 'required|date_format:H:i',
            'settings.*.end_time' => 'required|date_format:H:i|after:settings.*.start_time',
        ]);

        // Simple Full Replace Strategy for simplicity in this MVP
        // In real app, might want to be smarter to preserve IDs if needed, but for schedule matching, timing is key?
        // Actually schedule uses exact time matching or we'll switch schedule to use period_id?
        // Plan said: "Update ClassScheduleController to handle period inputs" 
        // Logic: Input Period -> Store Time.
        // So checking conflict uses TIME. 
        // Meaning we can delete and recreate settings without breaking schedules (as schedules store Time).

        try {
            return DB::transaction(function () use ($schoolId, $data) {
                SchoolTimeSetting::where('school_id', $schoolId)->delete();

                $count = 0;
                foreach ($data['settings'] as $setting) {
                    SchoolTimeSetting::create([
                        'school_id' => $schoolId,
                        'day_of_week' => $setting['day_of_week'],
                        'period_number' => $setting['period_number'] ?? null,
                        'label' => $setting['label'],
                        'start_time' => $setting['start_time'],
                        'end_time' => $setting['end_time'],
                    ]);
                    $count++;
                }

                return response()->json(['message' => 'Settings saved successfully', 'count' => $count]);
            });
        } catch (\Exception $e) {
            Log::error('Error saving time settings: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan pada server. Cek log untuk detail.'], 500);
        }
    }

    public function getSettings(Request $request)
    {
        $schoolId = $this->getSchoolId();
        if (!$schoolId) return response()->json([]);

        $day = $request->input('day_of_week');
        
        $settings = SchoolTimeSetting::where('school_id', $schoolId)
            ->where('day_of_week', $day)
            ->whereNotNull('period_number') // Only fetch valid teaching periods for dropdown
            ->orderBy('start_time')
            ->get();

        return response()->json($settings);
    }

    private function getSchoolId()
    {
        $user = Auth::user();
        return $user->school_id ?? $user->teacher?->school_id ?? $user->student?->school_id;
    }
}
