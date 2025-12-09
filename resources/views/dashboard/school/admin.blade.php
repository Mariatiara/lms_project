@extends('layouts.dashboard')

@section('content')
    <div class="space-y-8 animate-fade-in-up">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <div>
                <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-linear-to-r from-blue-600 to-purple-600">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="text-gray-500 mt-1">Ini ringkasan aktivitas sekolah Anda hari ini.</p>
            </div>
            <div
                class="mt-4 md:mt-0 bg-white shadow-sm px-4 py-2 rounded-lg border border-gray-100 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                <span class="text-sm font-medium text-gray-700">
                    Tahun Ajaran:
                    {{ $activeAcademicYear ? $activeAcademicYear->name . ' - ' . ucfirst($activeAcademicYear->semester) : 'Belum Ada' }}
                </span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Students -->
            <div
                class="relative overflow-hidden bg-linear-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300 group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Aktif</span>
                    </div>
                    <h3 class="text-4xl font-bold mb-1">{{ $totalStudents }}</h3>
                    <p class="text-blue-100 text-sm font-medium">Total Siswa</p>
                </div>
                <!-- Decorative circle -->
                <div
                    class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
            </div>

            <!-- Teachers -->
            <div
                class="relative overflow-hidden bg-linear-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300 group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">Aktif</span>
                    </div>
                    <h3 class="text-4xl font-bold mb-1">{{ $totalTeachers }}</h3>
                    <p class="text-purple-100 text-sm font-medium">Total Guru</p>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
            </div>

            <!-- Classrooms -->
            <div
                class="relative overflow-hidden bg-linear-to-br from-emerald-500 to-emerald-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transition-shadow duration-300 group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-white/20 p-2 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-4xl font-bold mb-1">{{ $totalClasses }}</h3>
                    <p class="text-emerald-100 text-sm font-medium">Jumlah Rombel</p>
                </div>
                <div
                    class="absolute -bottom-4 -right-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-150 transition-transform duration-500">
                </div>
            </div>
        </div>

        <!-- Data & Actions Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Data Completeness -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Status Kelengkapan Data
                    <span class="text-xs font-normal text-gray-500 ml-auto">Update Realtime</span>
                </h2>
                <div class="space-y-6">
                    <!-- Students Data -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-700">Data Siswa</span>
                            @if($studentDataProgress >= 80)
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Lengkap {{ $studentDataProgress }}%</span>
                            @else
                                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Perlu Dilengkapi ({{ $studentDataProgress }}%)</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $studentDataProgress }}%"></div>
                        </div>
                    </div>

                    <!-- Teachers Data -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-700">Data Guru</span>
                            @if($teacherDataProgress >= 80)
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Lengkap {{ $teacherDataProgress }}%</span>
                            @else
                                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Perlu Dilengkapi ({{ $teacherDataProgress }}%)</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-purple-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $teacherDataProgress }}%"></div>
                        </div>
                    </div>

                    <!-- School Data -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-700">Profil Sekolah</span>
                            @if($schoolDataProgress >= 100)
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Lengkap</span>
                            @else
                                <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Perlu Dilengkapi ({{ $schoolDataProgress }}%)</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-emerald-600 h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $schoolDataProgress }}%"></div>
                        </div>
                    </div>

                    <!-- Schedule Data -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-sm font-medium text-gray-700">Jadwal Pelajaran</span>
                            @if($scheduleCreated)
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Sudah Dibuat</span>
                            @else
                                <span class="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-lg">Belum Dibuat</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $scheduleCreated ? 'bg-emerald-500' : 'bg-red-500' }} h-2.5 rounded-full transition-all duration-1000 ease-out"
                                style="width: {{ $scheduleCreated ? '100' : '5' }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shortcuts -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Akses Cepat
                </h2>
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('master.students.index') }}"
                        class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div class="bg-blue-100 text-blue-600 p-2 rounded-lg group-hover:bg-blue-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-blue-700">Tambah Siswa</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('pengumuman.index') }}"
                        class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-purple-100 hover:bg-purple-50 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-purple-100 text-purple-600 p-2 rounded-lg group-hover:bg-purple-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-purple-700">Buat Pengumuman</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-500 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>

                    <a href="{{ route('rapor.index') }}"
                        class="group flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:border-emerald-100 hover:bg-emerald-50 transition-all duration-200">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-emerald-100 text-emerald-600 p-2 rounded-lg group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-700 group-hover:text-emerald-700">Cetak Rapor</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        </div>
    </div>
@endsection