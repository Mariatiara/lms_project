<div class="w-64 bg-slate-900 border-r border-slate-800 h-screen fixed left-0 top-0 flex flex-col shadow-2xl z-50">
    {{-- Header / Logo --}}
    <div class="h-16 flex items-center px-6 border-b border-slate-800">
        <div class="flex items-center gap-3 cursor-pointer">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                <span class="text-white font-bold text-lg">L</span>
            </div>
            <h1 class="text-xl font-bold text-white tracking-tight">LearnFlux</h1>
        </div>
    </div>

    @php
        use App\Enums\UserRole;
        $activeUser = Auth::user();
        $role = $activeUser->role; // This is now a UserRole Enum instance
        
        // Helper to check active route
        if (!function_exists('isActive')) {
            function isActive($patterns) {
                return request()->routeIs($patterns) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-900/20' : 'text-slate-400 hover:bg-slate-800 hover:text-white';
            }
        }

        // Determine dashboard route based on role
        $dashboardRoute = match($role) {
            UserRole::SUPERADMIN => 'superadmin.dashboard', // Added Superadmin Handling
            UserRole::DINAS => 'dashboard.dinas',
            UserRole::ADMIN_DINAS => 'dashboard.dinas.admin',
            UserRole::KEPALA_SEKOLAH => 'dashboard.headmaster',
            UserRole::ADMIN_SEKOLAH => 'dashboard.school.admin',
            UserRole::GURU => 'dashboard.teacher',
            UserRole::SISWA => 'dashboard.student',
            UserRole::ORANG_TUA => 'dashboard.parent',
            default => 'dashboard' // Fallback
        };
        // Check if teacher is Homeroom Teacher
        $isHomeroom = false;
        if ($role === UserRole::GURU && $activeUser->teacher) {
             $activeYearId = \App\Models\AcademicYear::where('is_active', true)->value('id');
             if ($activeYearId) {
                 $isHomeroom = \App\Models\Classroom::where('teacher_id', $activeUser->teacher->id)
                     ->where('academic_year_id', $activeYearId)
                     ->exists();
             }
        }
    @endphp

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
        
        {{-- DASHBOARD --}}
        <a href="{{ route($dashboardRoute) }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['dashboard.*', 'superadmin.dashboard']) }}">
            <div class="w-5 h-5 transition-transform group-hover:scale-110">
                @include('components.icons.dashboard')
            </div>
            <span class="font-medium">Dashboard</span>
        </a>

        {{-- ROLE SPECIFIC MENUS --}}
        
        {{-- MENU DINAS --}}
        @if ($role === UserRole::DINAS)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Administrasi
            </div>
            <a href="{{ route('superadmin.dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('superadmin.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.dashboard')
                </div>
                <span class="font-medium">Superadmin</span>
            </a>

            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                e-Laporan
            </div>
            
            <a href="{{ route('dinas.reports.incoming') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['dinas.reports.incoming', 'dinas.reports.show']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.laporan')
                </div>
                <span class="font-medium">Laporan Masuk</span>
            </a>

            <a href="{{ route('dinas.reports.archive') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('dinas.reports.archive') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                </div>
                <span class="font-medium">Arsip Laporan</span>
            </a>
        @endif

        {{-- MENU ADMIN DINAS --}}
        @if ($role === UserRole::ADMIN_DINAS)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Manajemen Sekolah
            </div>
            
            <a href="{{ route('dinas.schools.pending') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['dinas.schools.pending']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="font-medium">Verifikasi Sekolah</span>
            </a>

            <a href="{{ route('dinas.schools.active') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['dinas.schools.active']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="font-medium">Data Sekolah</span>
            </a>
        @endif
        
        {{-- MENU KEPALA SEKOLAH --}}
        @if ($role === UserRole::KEPALA_SEKOLAH)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Akademik
            </div>


            <a href="{{ route('academic.calendar.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.calendar.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Kalender Akademik</span>
            </a>

            <a href="{{ route('academic.schedule.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.schedule.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                   @include('components.icons.jadwal')
                </div>
                <span class="font-medium">Monitoring Jadwal</span>
            </a>

            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Administrasi
            </div>
            
            <a href="{{ route('school-reports.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['school-reports.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="font-medium">Laporan Sekolah</span>
            </a>
        @endif



        {{-- MENU ADMIN SEKOLAH --}}
        @if ($role === UserRole::ADMIN_SEKOLAH)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Akademik & Kurikulum
            </div>

            <a href="{{ route('academic.calendar.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.calendar.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Kalender Akademik</span>
            </a>

            <a href="{{ route('academic.schedule.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.schedule.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.jadwal')
                </div>
                <span class="font-medium">Manajemen Jadwal</span>
            </a>

            <a href="{{ route('school.grade-weights.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('school.grade-weights.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Bobot Nilai</span>
            </a>

            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Data Master
            </div>
            
            <a href="{{ route('master.academic-years.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.academic-years.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Tahun Ajaran</span>
            </a>

            <a href="{{ route('master.subjects.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.subjects.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="font-medium">Mata Pelajaran</span>
            </a>

            <a href="{{ route('master.courses.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.courses.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="font-medium">Pengajaran</span>
            </a>

            <a href="{{ route('master.classrooms.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.classrooms.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="font-medium">Data Kelas</span>
            </a>

            <a href="{{ route('master.teachers.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.teachers.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <span class="font-medium">Data Guru</span>
            </a>

            <a href="{{ route('master.students.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('master.students.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <span class="font-medium">Data Siswa</span>
            </a>
        @endif
        
        {{-- MENU GURU --}}
        @if ($role === UserRole::GURU)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Pengajaran
            </div>
            
            <a href="{{ route('teacher.courses.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['teacher.courses.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="font-medium">Kelas Saya</span>
            </a>
            
            <a href="{{ route('academic.calendar.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.calendar.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Kalender Akademik</span>
            </a>

            <a href="{{ route('academic.schedule.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['academic.schedule.*', 'jadwal.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.jadwal')
                </div>
                <span class="font-medium">Jadwal Mengajar</span>
            </a>



            {{-- MODULE WALI KELAS --}}
            @if(isset($isHomeroom) && $isHomeroom)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Wali Kelas
            </div>
            <a href="{{ route('homeroom.students.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('homeroom.students.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="font-medium">Anggota Kelas</span>
            </a>
            
            <a href="{{ route('homeroom.leger.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('homeroom.leger.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
                <span class="font-medium">Leger Nilai</span>
            </a>

            <a href="{{ route('homeroom.report.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['homeroom.report.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="font-medium">Manajemen Rapor</span>
            </a>
            @endif
        @endif

        {{-- MENU SISWA --}}
        @if ($role === UserRole::SISWA)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Pembelajaran
            </div>

            <a href="{{ route('student.courses.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['student.courses.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <span class="font-medium">Kelas Saya</span>
            </a>

            <a href="{{ route('student.exams.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['student.exams.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <span class="font-medium">Ujian / Kuis</span>
            </a>

            <a href="{{ route('academic.calendar.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('academic.calendar.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-medium">Kalender Akademik</span>
            </a>

            <a href="{{ route('student.schedule.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['student.schedule.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.jadwal')
                </div>
                <span class="font-medium">Jadwal Pelajaran</span>
            </a>

            <a href="{{ route('student.assignments.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['student.assignments.*']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.file-upload')
                </div>
                <span class="font-medium">Tugas Saya</span>
            </a>

            <a href="{{ route('rapor.index') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('rapor.*') }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.nilai')
                </div>
                <span class="font-medium">Nilai & Rapor</span>
            </a>
        @endif

        {{-- MENU ORANG TUA --}}
        @if ($role === UserRole::ORANG_TUA)
            <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
                Monitoring
            </div>
            <a href="{{ route('parent.attendance') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['parent.attendance']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <span class="font-medium">Kehadiran</span>
            </a>

            <a href="{{ route('parent.schedule') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['parent.schedule']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.jadwal')
                </div>
                <span class="font-medium">Jadwal Pelajaran</span>
            </a>

            <a href="{{ route('parent.grades') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive(['parent.grades']) }}">
                <div class="w-5 h-5 transition-transform group-hover:scale-110">
                    @include('components.icons.nilai')
                </div>
                <span class="font-medium">Nilai & Rapor</span>
            </a>
        @endif

        {{-- MENU UMUM (MESSAGES) --}}
        {{--
        <div class="mt-6 mb-2 text-xs font-semibold text-slate-500 uppercase tracking-wider px-3">
            Komunikasi
        </div>
        <a href="{{ route('chat.index') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group {{ isActive('chat.*') }}">
            <div class="w-5 h-5 transition-transform group-hover:scale-110">
               @include('components.icons.pesan')
            </div>
            <span class="font-medium">Pesan</span>
        </a>
        --}}
    </nav>

    {{-- Footer / User Profile & Logout --}}
    <div class="border-t border-slate-800 p-4 bg-slate-900/50">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-8 h-8 rounded-full bg-slate-700 flex items-center justify-center text-xs font-bold text-white uppercase">
                {{ substr(Auth::user()->name ?? 'U', 0, 2) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-slate-500 truncate capitalize">
                   {{-- Using label() method from Enum --}}
                   {{ $role->label() }}
                </p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="flex items-center justify-center space-x-2 w-full px-4 py-2 text-sm font-medium text-red-400 bg-red-400/10 hover:bg-red-400/20 rounded-lg transition-colors duration-200">
                @include('components.icons.logout')
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<style>
/* Custom scrollbar for better aesthetics */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
}
</style>
