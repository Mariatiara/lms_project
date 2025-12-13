@extends('layouts.dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <!-- Header with Gradient Banner -->
    <div class="relative rounded-3xl overflow-hidden bg-linear-to-r from-blue-600 to-indigo-700 shadow-xl">
        <div class="absolute inset-0 bg-white/5 opacity-50 pattern-grid-lg"></div>
        <div class="relative p-8 md:p-10 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                   <div class="flex items-center gap-2 text-blue-100 text-sm font-medium mb-2">
                        <a href="{{ route('teacher.courses.index') }}" class="hover:text-white transition-colors flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kelas Saya
                        </a>
                        <span class="opacity-50">/</span>
                        <span>Detail Kelas</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold tracking-tight">{{ $course->subject->name }}</h1>
                    <div class="flex items-center gap-4 mt-3 text-blue-100">
                        <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-semibold backdrop-blur-sm">{{ $course->classroom->name }}</span>
                        <span class="flex items-center gap-1 text-sm bg-white/20 px-3 py-1 rounded-full backdrop-blur-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $course->academicYear->name ?? 'Tahun Ajar' }}
                        </span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('teacher.gradebook.index', $course->id) }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl font-semibold backdrop-blur-sm transition-colors flex items-center gap-2 shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Buku Nilai
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Materi -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-0.5">Total Materi</p>
                <p class="text-2xl font-black text-gray-900">{{ $course->materials_count }}</p>
            </div>
        </div>

        <!-- Total Tugas -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center text-xl shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-0.5">Total Tugas</p>
                <p class="text-2xl font-black text-gray-900">{{ $course->assignments_count }}</p>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-0.5">Total Siswa</p>
                <p class="text-2xl font-black text-gray-900">{{ $totalStudents }}</p>
            </div>
        </div>

        <!-- Total Pertemuan -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-center gap-5">
            <div class="w-14 h-14 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-0.5">Total Pertemuan</p>
                <p class="text-2xl font-black text-gray-900">{{ $totalMeetings }}</p>
            </div>
        </div>
    </div>

    <!-- Tabs with Alpine.js -->
    <div x-data="{ activeTab: '{{ session('active_tab', 'materi') }}' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Tab Headers -->
        <div class="flex border-b border-gray-100 px-6 gap-6 overflow-x-auto">
            <button @click="activeTab = 'materi'" 
                    class="py-5 text-sm font-semibold transition-all relative"
                    :class="{ 'text-blue-600': activeTab === 'materi', 'text-gray-500 hover:text-gray-800': activeTab !== 'materi' }">
                Materi Pembelajaran
                <div x-show="activeTab === 'materi'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t-full" x-transition></div>
            </button>
            <button @click="activeTab = 'tugas'" 
                    class="py-5 text-sm font-semibold transition-all relative"
                    :class="{ 'text-blue-600': activeTab === 'tugas', 'text-gray-500 hover:text-gray-800': activeTab !== 'tugas' }">
                Tugas Kelas
                <div x-show="activeTab === 'tugas'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t-full" x-transition></div>
            </button>
            <button @click="activeTab = 'siswa'" 
                    class="py-5 text-sm font-semibold transition-all relative"
                    :class="{ 'text-blue-600': activeTab === 'siswa', 'text-gray-500 hover:text-gray-800': activeTab !== 'siswa' }">
                Daftar Siswa
                <div x-show="activeTab === 'siswa'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t-full" x-transition></div>
            </button>
            <button @click="activeTab = 'absensi'" 
                    class="py-5 text-sm font-semibold transition-all relative"
                    :class="{ 'text-blue-600': activeTab === 'absensi', 'text-gray-500 hover:text-gray-800': activeTab !== 'absensi' }">
                Absensi
                <div x-show="activeTab === 'absensi'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t-full" x-transition></div>
            </button>
            <button @click="activeTab = 'ujian'" 
                    class="py-5 text-sm font-semibold transition-all relative"
                    :class="{ 'text-blue-600': activeTab === 'ujian', 'text-gray-500 hover:text-gray-800': activeTab !== 'ujian' }">
                Ujian / Quiz
                <div x-show="activeTab === 'ujian'" class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 rounded-t-full" x-transition></div>
            </button>
        </div>

        <!-- Tab Contents -->
        <div class="p-6">
            
            <!-- Materi Tab -->
            <div x-show="activeTab === 'materi'" class="space-y-6">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Materi Pembelajaran</h3>
                    <button @click="$dispatch('open-modal', 'upload-materi')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Upload Materi
                    </button>
                </div>

                @if($course->materials->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <p class="text-gray-500 italic">Belum ada materi yang diunggah.</p>
                </div>
                @else
                <div class="grid gap-4">
                    @foreach($course->materials as $material)
                    <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row items-start md:items-center justify-between hover:bg-gray-50 transition gap-4">
                        <div class="flex items-start gap-4">
                            <div class="bg-blue-100 p-3 rounded-lg text-blue-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $material->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $material->description }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $material->created_at->format('d M Y, H:i') }} • {{ strtoupper($material->file_type) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                            <a href="{{ Storage::url($material->file_path) }}" target="_blank" class="px-3 py-1.5 text-blue-600 hover:bg-blue-50 rounded-lg text-sm font-medium transition border border-transparent hover:border-blue-100">
                                Download
                            </a>
                            <button @click="$dispatch('open-modal', 'edit-material'); $dispatch('set-edit-material', {{ $material->toJson() }})" class="px-3 py-1.5 text-yellow-600 hover:bg-yellow-50 rounded-lg text-sm font-medium transition border border-transparent hover:border-yellow-100">
                                Edit
                            </button>
                            <form action="{{ route('teacher.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1.5 text-red-500 hover:bg-red-50 rounded-lg text-sm font-medium transition border border-transparent hover:border-red-100">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Tugas Tab -->
            <div x-show="activeTab === 'tugas'" class="space-y-6" style="display: none;">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Tugas Kelas</h3>
                    <button @click="$dispatch('open-modal', 'create-assignment')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Tugas
                    </button>
                </div>

                @if($course->assignments->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <p class="text-gray-500 italic">Belum ada tugas yang diberikan.</p>
                </div>
                @else
                <div class="grid gap-4">
                    @foreach($course->assignments as $assignment)
                    <div class="border border-gray-200 rounded-lg p-4 flex flex-col md:flex-row items-start md:items-center justify-between hover:bg-gray-50 transition gap-4">
                        <div>
                            <h4 class="font-bold text-gray-800">{{ $assignment->title }}</h4>
                            <div class="flex items-center gap-4 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded bg-blue-100 text-blue-700 font-medium">
                                    Deadline: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    Dibuat: {{ $assignment->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                                Lihat Detail
                            </a>
                            <button @click="$dispatch('open-modal', 'edit-assignment'); $dispatch('set-edit-assignment', {{ $assignment->toJson() }})" class="px-3 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg text-sm font-medium transition border border-transparent">
                                Edit
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Siswa Tab -->
            <div x-show="activeTab === 'siswa'" class="space-y-6" style="display: none;">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Siswa</h3>
                    <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-bold">{{ $totalStudents }} Siswa</span>
                </div>
                
                @if($course->classroom->students->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <p class="text-gray-500 italic">Belum ada siswa di kelas ini.</p>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($course->classroom->students as $student)
                    <div class="bg-white border border-gray-100 rounded-2xl p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
                        <!-- Decorative bg -->
                        <div class="absolute top-0 left-0 right-0 h-16 bg-linear-to-br from-blue-50 to-indigo-50 z-0"></div>
                        
                        <!-- Avatar -->
                        <div class="w-16 h-16 rounded-full bg-white border-4 border-white shadow-sm flex items-center justify-center z-10 mb-3 text-xl font-bold text-blue-600 relative">
                             <div class="absolute inset-0 rounded-full bg-linear-to-br from-blue-100 to-indigo-100 opacity-50"></div>
                             <span class="relative">{{ substr($student->user->name, 0, 2) }}</span>
                        </div>
                        
                        <!-- Info -->
                        <div class="z-10 w-full">
                            <h4 class="font-bold text-gray-900 truncate w-full" title="{{ $student->user->name }}">{{ $student->user->name }}</h4>
                            <p class="text-sm text-gray-500 font-mono mt-1">{{ $student->nis }}</p>
                            
                            <div class="mt-4 pt-4 border-t border-gray-50 w-full flex justify-center">
                                <span class="bg-green-50 text-green-700 px-3 py-1 rounded-full text-xs font-bold border border-green-100 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Absensi Tab -->
            <div x-show="activeTab === 'absensi'" class="space-y-6" style="display: none;">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Kehadiran</h3>
                        <p class="text-sm text-gray-500">Rekapitulasi kehadiran siswa per pertemuan</p>
                    </div>
                    @if(auth()->user()->teacher->id === $course->teacher_id)
                    <button @click="$dispatch('open-modal', 'attendance-modal')" 
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/50 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        Absensi Hari Ini
                    </button>
                    @endif
                </div>
                
                @if($attendanceHistory->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Belum ada riwayat absensi</h3>
                    <p class="text-gray-500 mt-1">Mulai lakukan absensi untuk melihat rekapitulasi di sini.</p>
                </div>
                @else
                <div class="space-y-4">
                    @foreach($attendanceHistory as $record)
                    <div class="bg-white border border-gray-100 rounded-xl p-5 hover:shadow-md transition-all flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-xl bg-blue-50 text-blue-600 flex flex-col items-center justify-center shrink-0">
                                <span class="text-xs font-bold uppercase">{{ $record['date']->format('M') }}</span>
                                <span class="text-xl font-black heading-font">{{ $record['date']->format('d') }}</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $record['date']->format('l, d F Y') }}</h4>
                                <p class="text-sm text-gray-500">{{ $record['total'] }} Siswa Terdata</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-100" title="Hadir">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                {{ $record['present'] }} Hadir
                            </span>
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-700 border border-yellow-100" title="Sakit">
                                <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                                {{ $record['sick'] }} Sakit
                            </span>
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100" title="Izin">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                {{ $record['permit'] }} Izin
                            </span>
                             <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-100" title="Alpha">
                                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                {{ $record['alpha'] }} Alpha
                            </span>
                            
                            @if(auth()->user()->teacher->id === $course->teacher_id)
                            <button @click="$dispatch('edit-attendance', '{{ $record['date']->format('Y-m-d') }}')" 
                                    class="ml-2 text-gray-400 hover:text-blue-600 transition-colors" title="Edit Absensi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Ujian Tab -->
            <div x-show="activeTab === 'ujian'" class="space-y-6" style="display: none;">
                <div class="flex justify-between items-center flex-wrap gap-4">
                    <h3 class="text-lg font-semibold text-gray-800">Daftar Ujian & Kuis</h3>
                    <a href="{{ route('teacher.exams.create', $course->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Ujian Baru
                    </a>
                </div>

                @if($course->exams->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <p class="text-gray-500 italic">Belum ada ujian yang dibuat.</p>
                </div>
                @else
                <div class="grid gap-4">
                    @foreach($course->exams as $exam)
                    <div class="border border-gray-100 rounded-lg p-4 flex flex-col md:flex-row items-start md:items-center justify-between hover:bg-gray-50 transition gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h4 class="font-bold text-gray-800">{{ $exam->title }}</h4>
                                @if($exam->is_published)
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Terbit</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">Draft</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $exam->duration_minutes }} Menit
                                </span>
                                <span class="flex items-center gap-1">
                                     <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                    {{ $exam->questions->count() }} Soal
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <a href="{{ route('teacher.exams.questions', $exam->id) }}" class="px-3 py-1.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-xs font-medium hover:bg-gray-50 transition">
                                Kelola Soal
                            </a>
                            <a href="{{ route('teacher.exams.edit', $exam->id) }}" class="px-3 py-1.5 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg text-xs font-medium transition border border-transparent">
                                Edit
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="text-center mt-4 pt-4 border-t border-gray-100">
                    <a href="{{ route('teacher.exams.index', $course->id) }}" class="text-blue-600 hover:text-blue-700 font-medium text-sm inline-flex items-center gap-1">
                        Lihat Semua Ujian
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Upload Materi -->
<x-modal name="upload-materi" :show="$errors->has('title') || $errors->has('file') ? true : false">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Upload Materi Baru</h3>
            <button @click="$dispatch('close-modal', 'upload-materi')" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('teacher.courses.materials.store', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border" placeholder="Contoh: Modul Bab 1">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border" placeholder="Deskripsi singkat tentang materi ini..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
                    <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                    <p class="text-xs text-gray-500 mt-2">Format yang didukung: PDF, DOCX, PPTX. Maksimal 10MB.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                <button type="button" @click="$dispatch('close-modal', 'upload-materi')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Upload</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Edit Materi -->
<x-modal name="edit-material" :show="false">
    <div class="p-6" x-data="{ material: {}, actionUrl: '' }" @set-edit-material.window="material = $event.detail; actionUrl = '{{ route('teacher.materials.update', ':id') }}'.replace(':id', material.id)">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Materi</h3>
            <button @click="$dispatch('close-modal', 'edit-material')" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form :action="actionUrl" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Materi</label>
                    <input type="text" name="title" x-model="material.title" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" x-model="material.description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ganti File (Opsional)</label>
                    <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                    <p class="text-xs text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengganti file. Format: PDF, DOCX, PPTX.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                <button type="button" @click="$dispatch('close-modal', 'edit-material')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-modal>


<!-- Modal Create Assignment -->
<x-modal name="create-assignment" :show="$errors->has('due_date') ? true : false">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Buat Tugas Baru</h3>
            <button @click="$dispatch('close-modal', 'create-assignment')" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('teacher.courses.assignments.store', $course->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                    <input type="text" name="title" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border" placeholder="Contoh: Tugas Harian 1">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border" placeholder="Berikan instruksi detail tugas..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="due_date" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                <button type="button" @click="$dispatch('close-modal', 'create-assignment')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Simpan Tugas</button>
            </div>
        </form>
    </div>
</x-modal>

<!-- Modal Edit Assignment -->
<x-modal name="edit-assignment" :show="false">
    <div class="p-6" x-data="{ assignment: {}, actionUrl: '' }" @set-edit-assignment.window="assignment = $event.detail; actionUrl = '{{ route('teacher.assignments.update', ':id') }}'.replace(':id', assignment.id)">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Tugas</h3>
            <button @click="$dispatch('close-modal', 'edit-assignment')" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form :action="actionUrl" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Tugas</label>
                    <input type="text" name="title" x-model="assignment.title" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="description" x-model="assignment.description" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Batas Waktu (Deadline)</label>
                    <input type="datetime-local" name="due_date" x-model="assignment.due_date" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t">
                <button type="button" @click="$dispatch('close-modal', 'edit-assignment')" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</x-modal>
    <!-- Modal Input Absensi -->
<x-modal name="attendance-modal" focusable>
    <div class="bg-white p-6 rounded-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" x-data="{
        date: '{{ now()->format('Y-m-d') }}',
        attendanceData: {},
        isLoading: false,
        
        async init() {
            // Determine if we are just opening or editing
        },

        async fetchData(targetDate) {
            this.isLoading = true;
            this.date = targetDate;
            
            try {
                // Fetch existing attendance data for this date
                const response = await fetch('{{ route('teacher.courses.attendance.show', ['id' => $course->id, 'date' => ':date']) }}'.replace(':date', targetDate));
                const data = await response.json();
                
                // Reset all radios first
                document.querySelectorAll('input[type=radio]').forEach(r => r.checked = false);
                
                // Map db enum values back to H/I/S/A
                // present -> H
                // permission -> I
                // sick -> S
                // absent -> A
                
                const reverseMap = {
                    'present': 'H',
                    'permission': 'I',
                    'sick': 'S',
                    'absent': 'A'
                };

                // Fill form
                for (const [studentId, status] of Object.entries(data)) {
                    const shortStatus = reverseMap[status];
                    const radio = document.querySelector(`input[name='attendance[${studentId}]'][value='${shortStatus}']`);
                    if(radio) radio.checked = true;
                }

            } catch (error) {
                console.error('Error fetching attendance:', error);
            } finally {
                this.isLoading = false;
            }
        },

        selectAll(status) {
            document.querySelectorAll('input[type=radio][value=' + status + ']').forEach(r => r.checked = true);
        }
    }"
    @edit-attendance.window="$dispatch('open-modal', 'attendance-modal'); fetchData($event.detail);">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Absensi Kelas</h2>
                <p class="text-gray-500 text-sm">Catat atau ubah kehadiran siswa.</p>
            </div>
            <button type="button" x-on:click="$dispatch('close-modal', 'attendance-modal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form action="{{ route('teacher.courses.attendance.store', $course->id) }}" method="POST" class="flex-1 overflow-hidden flex flex-col relative">
            @csrf
            
            <!-- Loading Overlay -->
            <div x-show="isLoading" class="absolute inset-0 bg-white/80 z-20 flex items-center justify-center backdrop-blur-sm">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Pertemuan</label>
                <input type="date" name="date" x-model="date" @change="fetchData(date)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
                    <div class="flex justify-end gap-2 mb-4 sticky top-0 bg-white py-2 z-10 border-b">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider self-center mr-2">Set Semua:</span>
                    <button type="button" @click="selectAll('H')" class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 hover:bg-green-200">Hadir Semua</button>
                    <button type="button" @click="selectAll('S')" class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-700 hover:bg-yellow-200">Sakit Semua</button>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @foreach($course->classroom->students as $student)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                {{ substr($student->user->name, 0, 2) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $student->user->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $student->nis }}</p>
                            </div>
                        </div>
                        
                        <div class="flex gap-4">
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="H" class="peer sr-only" required>
                                <div class="w-8 h-8 rounded-full border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 text-transparent peer-checked:text-green-600 flex items-center justify-center transition-all">
                                    <span class="font-bold text-xs">H</span>
                                </div>
                                <span class="text-[10px] font-medium text-gray-400 group-hover:text-gray-600 mt-1">Hadir</span>
                            </label>
                            
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="I" class="peer sr-only">
                                <div class="w-8 h-8 rounded-full border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 text-transparent peer-checked:text-blue-600 flex items-center justify-center transition-all">
                                    <span class="font-bold text-xs">I</span>
                                </div>
                                <span class="text-[10px] font-medium text-gray-400 group-hover:text-gray-600 mt-1">Izin</span>
                            </label>
                            
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="S" class="peer sr-only">
                                <div class="w-8 h-8 rounded-full border-2 border-gray-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 text-transparent peer-checked:text-yellow-600 flex items-center justify-center transition-all">
                                    <span class="font-bold text-xs">S</span>
                                </div>
                                <span class="text-[10px] font-medium text-gray-400 group-hover:text-gray-600 mt-1">Sakit</span>
                            </label>

                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="radio" name="attendance[{{ $student->id }}]" value="A" class="peer sr-only">
                                <div class="w-8 h-8 rounded-full border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 text-transparent peer-checked:text-red-600 flex items-center justify-center transition-all">
                                    <span class="font-bold text-xs">A</span>
                                </div>
                                <span class="text-[10px] font-medium text-gray-400 group-hover:text-gray-600 mt-1">Alpha</span>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex justify-end gap-3 rounded-none sticky bottom-0 bg-white">
                    <button type="button" x-on:click="$dispatch('close-modal', 'attendance-modal')" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-medium hover:bg-gray-50 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all">Simpan Absensi</button>
            </div>
        </form>

    </div>
</x-modal>
@endsection
