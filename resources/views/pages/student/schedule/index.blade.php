@extends('layouts.dashboard')

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Pelajaran</h1>
            <p class="text-gray-500">Jadwal kelas mingguan kamu.</p>
        </div>
        <div class="text-gray-500 text-sm">
             Tahun Ajaran Aktif
        </div>
    </div>

    @if($schedules->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
            <p class="text-gray-500">Belum ada jadwal yang tersedia.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            @php
                 // Sort order for days
                 $daysOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                 $groupedSchedules = $schedules->groupBy('day_of_week')->sortBy(function($shedules, $key) use ($daysOrder) {
                     return array_search(strtolower($key), $daysOrder);
                 });
                 
                 // Mapper for Indonesian day names
                 $dayNames = [
                     'monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                     'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'
                 ];
            @endphp

            @foreach($daysOrder as $day)
                @if(isset($groupedSchedules[$day]))
                    <div class="space-y-4">
                        <div class="bg-blue-600 text-white text-center py-2 rounded-lg font-bold shadow-md">
                            {{ $dayNames[$day] ?? ucfirst($day) }}
                        </div>
                        
                        @foreach($groupedSchedules[$day]->sortBy('start_time') as $schedule)
                        <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-400 hover:shadow-md transition-shadow">
                            <div class="text-xs text-gray-500 font-bold mb-1">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </div>
                            <h3 class="font-bold text-gray-800 leading-tight">{{ $schedule->course->subject->name ?? 'Mapel' }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $schedule->course->teacher->user->name ?? 'Guru' }}</p>
                        </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
