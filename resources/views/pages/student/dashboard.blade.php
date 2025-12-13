@extends('layouts.dashboard')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="relative bg-white rounded-2xl p-8 shadow-sm border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-blue-50 rounded-full blur-3xl opacity-50"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Halo, {{ explode(' ', $student->nama ?? Auth::user()->name)[0] }}! 👋</h1>
                <p class="text-gray-500 mt-2 text-lg">Siap untuk belajar hal baru hari ini?</p>
            </div>
            <div class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-xl border border-gray-100">
                <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Hari Ini</p>
                    <p class="text-sm font-bold text-gray-800">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Card 1: Courses --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Mapel</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $courseCount }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Kelas Aktif</p>
                </div>
                <a href="{{ route('student.courses.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">Lihat &rarr;</a>
            </div>
        </div>

        {{-- Card 2: Tasks --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tugas</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $pendingTasksCount }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Belum Selesai</p>
                </div>
                <a href="{{ route('student.assignments.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">Cek &rarr;</a>
            </div>
        </div>

        {{-- Card 3: Attendance --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Kehadiran</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $attendancePercentage }}<span class="text-lg text-gray-400 font-semibold">%</span></h3>
                    <p class="text-sm text-gray-500 mt-1">Total Hadir</p>
                </div>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-1.5 mt-3 overflow-hidden">
                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $attendancePercentage }}%"></div>
            </div>
        </div>

        {{-- Card 4: Average Score --}}
        <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata</span>
            </div>
            <div class="flex items-end justify-between">
                <div>
                    <h3 class="text-3xl font-bold text-gray-900">{{ number_format($avgScore, 2) }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Nilai Akademik</p>
                </div>
                <span class="text-xs px-2 py-1 bg-emerald-50 text-emerald-700 rounded-full font-bold">Good Job!</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Left Column: Schedule (2/3) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-1 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xl font-bold text-gray-900">Jadwal Hari Ini</h2>
                </div>
                <a href="{{ route('student.schedule.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-50 hover:text-gray-900 transition-colors">
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
                    <h3 class="text-lg font-bold text-gray-900">Tidak Ada Kelas</h3>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Hore! Hari ini kamu bebas dari jadwal pelajaran. Manfaatkan waktumu untuk belajar mandiri atau istirahat.</p>
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
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                {{ $schedule->course->teacher->user->name ?? 'Guru Pengampu' }}
                                            </p>
                                        </div>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-medium">
                                            s/d {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-gray-50 flex items-center justify-between">
                                        <span class="text-xs text-gray-400">{{ $schedule->course->classroom->name ?? 'Kelas' }}</span>
                                        <a href="{{ route('student.courses.show', $schedule->course_id) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                                            Masuk Kelas 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right Column: Deadlines & Exams (1/3) --}}
        <div class="space-y-8">
            {{-- Upcoming Tasks --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Tugas Terdekat</h2>
                    <a href="{{ route('student.assignments.index') }}" class="text-sm font-medium text-orange-600 hover:text-orange-700">Lihat Semua</a>
                </div>

                @if($upcomingTasks->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                        <div class="bg-green-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-gray-900 font-medium text-sm">Semua Beres!</p>
                        <p class="text-xs text-gray-500 mt-1">Belum ada tugas baru.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($upcomingTasks as $task)
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-orange-200 transition-all cursor-pointer group">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-orange-600 transition-colors">{{ $task->title }}</h4>
                                        <p class="text-xs text-gray-500 mt-1">{{ $task->course->subject->name }}</p>
                                    </div>
                                    <div class="ml-3 shrink-0">
                                        <span class="inline-flex items-center px-2 py-1 rounded bg-orange-50 text-xs font-medium text-orange-700">
                                            {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans(null, true, true) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-3 flex justify-end">
                                    <a href="{{ route('student.courses.show', $task->course_id) }}?tab=assignments" class="text-xs font-semibold text-gray-500 hover:text-orange-600 transition-colors">Kerjakan &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Upcoming Exams --}}
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Ujian & Kuis</h2>
                    <a href="{{ route('student.exams.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700">Lihat Semua</a>
                </div>

                @if($upcomingExams->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 p-6 text-center">
                        <p class="text-gray-500 text-sm">Tidak ada jadwal ujian.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($upcomingExams as $exam)
                            @php
                                $now = now();
                                $isOpen = $now->between($exam->start_time, $exam->end_time);
                                $isFuture = $exam->start_time->gt($now);
                            @endphp
                            <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm hover:shadow-md hover:border-purple-200 transition-all group {{ $isOpen ? 'ring-2 ring-purple-500 ring-offset-2' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="bg-purple-50 text-purple-700 text-xs font-bold px-2 py-1 rounded-md">
                                        {{ $exam->start_time->format('d M, H:i') }}
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">{{ $exam->duration_minutes }}m</span>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 mt-2 group-hover:text-purple-600 transition-colors">{{ $exam->title }}</h4>
                                <p class="text-xs text-gray-500">{{ $exam->course->subject->name }}</p>

                                <div class="mt-3">
                                    @if($isOpen)
                                        <a href="{{ route('student.exams.show', $exam->id) }}" class="block w-full text-center py-2 bg-purple-600 text-white text-xs font-bold rounded-lg hover:bg-purple-700 transition-colors shadow-lg shadow-purple-500/30">
                                            Kerjakan Sekarang
                                        </a>
                                    @elseif($isFuture)
                                        <div class="block w-full text-center py-2 bg-gray-50 text-gray-400 text-xs font-bold rounded-lg cursor-not-allowed">
                                            Belum Dimulai
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
