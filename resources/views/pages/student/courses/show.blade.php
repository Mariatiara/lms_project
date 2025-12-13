@extends('layouts.dashboard')

@section('title', $course->subject->name)

@section('content')
<div class="p-6 space-y-6" x-data="{ activeTab: 'materi' }">
    
    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-gray-500 mb-4">
        <a href="{{ route('student.courses.index') }}" class="hover:text-blue-600">Kelas Saya</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800 font-medium">{{ $course->subject->name }}</span>
    </nav>

    {{-- Course Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                 <h1 class="text-2xl font-bold text-gray-900">{{ $course->subject->name }}</h1>
                 <p class="text-gray-500 mt-1">Pengajar: <span class="font-medium text-gray-800">{{ $course->teacher->user->name ?? '-' }}</span></p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-2">
                 {{-- Stats Summary --}}
            </div>
        </div>

        {{-- Progress Bars --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            {{-- Material Progress --}}
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Progress Materi</span>
                    <span class="font-bold text-blue-600">{{ $materialProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $materialProgress }}%"></div>
                </div>
            </div>

             {{-- Assignment Progress --}}
             <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Pengerjaan Tugas</span>
                    <span class="font-bold text-orange-500">{{ $assignmentProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $assignmentProgress }}%"></div>
                </div>
            </div>

            {{-- Attendance Progress --}}
             <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600">Kehadiran</span>
                    <span class="font-bold text-purple-600">{{ $attendanceProgress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $attendanceProgress }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Content Tabs --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 min-h-[500px]">
        {{-- Tab Headers --}}
        <div class="flex border-b border-gray-100">
            <button @click="activeTab = 'materi'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'materi', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'materi' }" class="flex-1 py-4 text-center font-medium text-sm border-b-2 transition-colors">
                Materi Belajar
            </button>
            <button @click="activeTab = 'tugas'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'tugas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'tugas' }" class="flex-1 py-4 text-center font-medium text-sm border-b-2 transition-colors">
                Tugas & Penilaian
            </button>
            <button @click="activeTab = 'absensi'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'absensi', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'absensi' }" class="flex-1 py-4 text-center font-medium text-sm border-b-2 transition-colors">
                Riwayat Absensi
            </button>
            <button @click="activeTab = 'ujian'" :class="{ 'border-blue-500 text-blue-600': activeTab === 'ujian', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'ujian' }" class="flex-1 py-4 text-center font-medium text-sm border-b-2 transition-colors">
                Ujian / Quiz
            </button>
        </div>

        {{-- Tab Contents --}}
        <div class="p-6">
            {{-- Materi Tab --}}
            <div x-show="activeTab === 'materi'" class="space-y-4">
                @forelse($course->materials as $material)
                    @php
                        $isCompleted = \App\Models\CourseMaterialCompletion::where('student_id', $student->id)
                                        ->where('course_material_id', $material->id)
                                        ->exists();
                    @endphp
                    <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 transition-colors flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div class="flex items-start space-x-4">
                            <div class="bg-blue-50 p-3 rounded-lg text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $material->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($material->description, 100) }}</p>
                                <div class="mt-2 flex items-center space-x-2 text-xs text-gray-400">
                                    <span>Added {{ $material->created_at->diffForHumans() }}</span>
                                    <span>&bull;</span>
                                    <span>{{ strtoupper($material->file_type ?? 'FILE') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex items-center space-x-3">
                            <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                                Download
                            </a>
                            
                            @if($isCompleted)
                                <span class="px-3 py-2 text-green-600 bg-green-50 rounded-lg text-sm font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Selesai
                                </span>
                            @else
                                <form action="{{ route('student.materials.complete', $material->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 border border-blue-600 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition-colors">
                                        Tandai Selesai
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500">Belum ada materi pelajaran.</p>
                    </div>
                @endforelse
            </div>

            {{-- Tugas Tab --}}
            <div x-show="activeTab === 'tugas'" class="space-y-4" style="display: none;">
                 @forelse($course->assignments as $assignment)
                    @php
                        $submission = $assignment->submissions->where('student_id', $student->id)->first();
                        $isLate = \Carbon\Carbon::now()->gt($assignment->due_date) && !$submission;
                    @endphp
                    <div class="border border-gray-100 rounded-xl p-4 hover:border-orange-200 transition-colors">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                             <div>
                                 <div class="flex items-center space-x-2">
                                     <h4 class="font-bold text-gray-800">{{ $assignment->title }}</h4>
                                     @if($isLate)
                                        <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded font-bold">Terlambat</span>
                                     @endif
                                 </div>
                                 <p class="text-sm text-gray-500 mt-1">Due Date: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}</p>
                             </div>
                             <div class="mt-2 md:mt-0">
                                 @if($submission)
                                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full font-medium">Sudah Dikumpulkan</span>
                                 @else
                                    <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full font-medium">Belum Dikumpulkan</span>
                                 @endif
                             </div>
                         </div>
                         
                         <div class="flex justify-end">
                              <a href="{{ route('student.assignments.show', $assignment->id) }}" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-sm font-medium transition-colors">
                                  {{ $submission ? 'Lihat Submission' : 'Kerjakan Tugas' }}
                              </a>
                         </div>
                    </div>
                 @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500">Belum ada tugas.</p>
                    </div>
                 @endforelse
            </div>

            {{-- Absensi Tab --}}
             <div x-show="activeTab === 'absensi'" style="display: none;">
                @php
                    $attendances = \App\Models\Attendance::where('course_id', $course->id)
                                    ->where('student_id', $student->id)
                                    ->orderBy('date', 'desc')
                                    ->get();
                @endphp
                 <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-500 border-b border-gray-100 bg-gray-50">
                                <th class="p-3 rounded-tl-lg">Tanggal</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 rounded-tr-lg">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendances as $attendance)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="p-3">{{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}</td>
                                <td class="p-3">
                                    @if($attendance->status == 'present')
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Hadir</span>
                                    @elseif($attendance->status == 'absent')
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Absen</span>
                                    @elseif($attendance->status == 'sick')
                                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Sakit</span>
                                    @else
                                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">Izin/Lainnya</span>
                                    @endif
                                </td>
                                <td class="p-3 text-gray-500 text-sm">-</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-6 text-center text-gray-500">Belum ada data absensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
            </div>
            </div>

            {{-- Ujian Tab --}}
            <div x-show="activeTab === 'ujian'" class="space-y-4" style="display: none;">
                @forelse($course->exams as $exam)
                    @php
                        $attempt = $exam->attempts->first();
                        // Force Jakarta timezone for logic
                        $now = \Carbon\Carbon::now('Asia/Jakarta');
                        $startTime = \Carbon\Carbon::parse($exam->start_time)->shiftTimezone('Asia/Jakarta');
                        $endTime = \Carbon\Carbon::parse($exam->end_time)->shiftTimezone('Asia/Jakarta');
                        
                        $isOngoing = $now->between($startTime, $endTime);
                        $isUpcoming = $now->lt($startTime);
                        $isFinished = $now->gt($endTime);
                    @endphp
                    <div class="border border-gray-100 rounded-xl p-4 hover:border-blue-200 transition-colors">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                             <div>
                                 <h4 class="font-bold text-gray-800">{{ $exam->title }}</h4>
                                 <p class="text-xs text-gray-500 mt-1">
                                    {{ $startTime->format('d M Y, H:i') }} - {{ $endTime->format('H:i') }} WIB 
                                    ({{ $exam->duration_minutes }} Menit)
                                 </p>
                             </div>
                             <div class="mt-2 md:mt-0">
                                 @if($attempt)
                                    <span class="bg-green-100 text-green-700 text-sm px-3 py-1 rounded-full font-medium">Selesai Dikerjakan</span>
                                 @elseif($isUpcoming)
                                    <span class="bg-yellow-100 text-yellow-700 text-sm px-3 py-1 rounded-full font-medium">Belum Dimulai</span>
                                 @elseif($isFinished)
                                    <span class="bg-red-100 text-red-700 text-sm px-3 py-1 rounded-full font-medium">Berakhir</span>
                                 @elseif($isOngoing)
                                    <span class="bg-blue-100 text-blue-700 text-sm px-3 py-1 rounded-full font-medium">Sedang Berlangsung</span>
                                 @endif
                             </div>
                         </div>
                         
                         <div class="flex justify-between items-center pt-3 border-t border-gray-50 mt-3">
                            <span class="text-xs text-gray-500 font-medium">{{ $exam->questions_count ?? 0 }} Soal</span>
                            
                            @if($attempt)
                                <a href="{{ route('student.exams.result', $exam->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    Lihat Hasil
                                </a>
                            @elseif($isOngoing)
                                <a href="{{ route('student.exams.show', $exam->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    Mulai Ujian
                                </a>
                            @endif
                         </div>
                    </div>
                 @empty
                    <div class="text-center py-10">
                        <p class="text-gray-500">Belum ada ujian.</p>
                    </div>
                 @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
