@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Halo, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p class="text-gray-500 mt-2 text-lg">Siap mengajar dan menginspirasi siswa hari ini?</p>
            </div>
            <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Hari Ini</p>
                    <p class="text-sm font-bold text-gray-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Total Classes --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kelas Aktif</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalActiveCourses }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Total Kelas</p>
                </div>
                <a href="{{ route('teacher.courses.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">Kelola &rarr;</a>
            </div>
        </div>

        {{-- Card 2: Total Students --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Siswa</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalStudents }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Siswa Dididik</p>
                </div>
            </div>
        </div>

        {{-- Card 3: Active Assignments --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center group-hover:bg-yellow-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tugas Aktif</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $activeAssignmentsCount }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Deadline Mendatang</p>
                </div>
            </div>
        </div>

        {{-- Card 4: Avg Attendance --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-green-50 text-green-600 flex items-center justify-center group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kehadiran</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $averageAttendance }}<span class="text-lg text-gray-400 font-semibold">%</span></h3>
                    <p class="text-sm text-gray-500 mt-1">Rata-rata Kelas</p>
                </div>
                <div class="w-16 bg-gray-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $averageAttendance }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Schedule (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xl font-bold text-gray-900">Jadwal Mengajar Hari Ini</h2>
                </div>
                <a href="{{ route('academic.schedule.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    Lihat Lengkap
                </a>
            </div>

            @if($todaysClasses->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100 border-dashed">
                    <div class="bg-blue-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Tidak Ada Jadwal Mengajar</h3>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Hari ini Anda tidak memiliki jadwal mengajar. Waktu yang tepat untuk menyiapkan materi atau menilai tugas.</p>
                </div>
            @else
                <div class="relative pl-8 space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-linear-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                    @foreach($todaysClasses as $schedule)
                        <div class="relative group">
                            <div class="absolute -left-10 mt-1 rounded-full bg-white border-4 border-blue-500 w-5 h-5 z-10 group-hover:scale-125 transition-transform"></div>
                            
                            <div class="flex flex-col md:flex-row gap-4 bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-200 transition-all">
                                <div class="shrink-0 flex flex-col items-center justify-center bg-blue-50 rounded-lg p-3 min-w-[80px]">
                                    <span class="text-xs font-bold text-blue-600 uppercase">Mulai</span>
                                    <span class="text-xl font-bold text-gray-900">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                                </div>
                                
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $schedule->course->subject->name ?? 'Mata Pelajaran' }}</h3>
                                            <p class="text-gray-500 text-sm flex items-center gap-1 mt-1">
                                                <span class="font-semibold text-gray-700">{{ $schedule->course->classroom->name }}</span>
                                            </p>
                                        </div>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-medium">
                                            s/d {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-end gap-3">
                                        <a href="{{ route('teacher.courses.show', $schedule->course_id) }}?tab=absensi" class="text-sm font-medium text-gray-600 hover:text-blue-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            Absensi
                                        </a>
                                        <a href="{{ route('teacher.courses.show', $schedule->course_id) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-blue-200">
                                            Masuk Kelas
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Column: Need Grading & Quick Stats (1/3) --}}
        <div class="space-y-8">
            {{-- Pending Grading --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Perlu Dinilai</h2>
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">{{ $pendingGrading->count() }} Tugas</span>
                </div>

                @if($pendingGrading->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                        <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-gray-900 font-medium text-sm">Semua Tugas Ternilai!</p>
                        <p class="text-xs text-gray-500 mt-1">Belum ada tugas baru yang perlu diperiksa.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($pendingGrading as $assignment)
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-yellow-200 transition-all cursor-pointer group relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-yellow-400"></div>
                                <div class="pl-2">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-yellow-600 transition-colors">{{ $assignment->title }}</h4>
                                            <p class="text-xs text-gray-500 mt-1">{{ $assignment->course->classroom->name }} &bull; {{ $assignment->course->subject->name }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 flex justify-between items-center">
                                         <span class="inline-flex items-center px-2 py-1 rounded bg-red-50 text-xs font-bold text-red-600">
                                            {{ $assignment->pending_count }} Siswa Menunggu
                                        </span>
                                        <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors">Nilai Sekarang &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Chart Section (Simplified for Sidebar) --}}
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Statistik Siswa per Kelas</h3>
                <div class="h-48">
                     <canvas id="studentsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('studentsChart').getContext('2d');
        const data = @json($studentsPerClass);
        
        new Chart(ctx, {
            type: 'doughnut', // Changed to Doughnut for sidebar fit
            data: {
                labels: data.map(item => item.class_name),
                datasets: [{
                    data: data.map(item => item.student_count),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)', // Blue
                        'rgba(16, 185, 129, 0.8)', // Green
                        'rgba(245, 158, 11, 0.8)', // Yellow
                        'rgba(139, 92, 246, 0.8)', // Purple
                        'rgba(236, 72, 153, 0.8)', // Pink
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: { size: 10 }
                        }
                    }
                },
                cutout: '70%',
            }
        });
    });
</script>
@endsection
