<div class="w-64 bg-white shadow-lg h-screen fixed left-0 top-0 p-6">
    <h1 class="text-xl font-bold mb-8">LearnFlux</h1>

    @php $role = Auth::user()->role; @endphp

    <ul class="space-y-4">

        {{-- ===========================
            DASHBOARD
        ============================ --}}
        <li>
            <a href="{{ 
                $role == 'kepala_sekolah' ? route('dashboard.kepsek') :
                ($role == 'guru' ? route('dashboard.guru') :
                ($role == 'siswa' ? route('dashboard.siswa') :
                ($role == 'orang_tua' ? route('dashboard.orangtua') :
                route('dashboard.dinas')))) 
            }}"
            class="flex items-center space-x-3 text-gray-700 hover:text-blue-600">
                @include('components.icons.dashboard')
                <span>Dashboard</span>
            </a>
        </li>


        {{-- ===========================
            MENU DINAS
        ============================ --}}
        @if ($role == 'dinas')
            <li>
                <a href="{{ route('dashboard.dinas') }}" class="flex items-center gap-3">
                    @include('components.icons.dashboard-dinas')
                    <span>Dashboard Dinas</span>
                </a>
            </li>

            <li>
                <a href="{{ route('superadmin.dashboard') }}" class="sidebar-item">
                    @include('components.icons.dashboard')
                    <span>Superadmin</span>
                </a>
            </li>
        @endif


        {{-- ===========================
            MENU KEPALA SEKOLAH
        ============================ --}}
        @if ($role == 'kepala_sekolah')
            <li>
                <a href="{{ route('dashboard.kepsek') }}" class="sidebar-item">
                    @include('components.icons.laporan')
                    <span>Laporan Nilai</span>
                </a>
            </li>
        @endif


        {{-- ===========================
            MENU GURU
        ============================ --}}
        @if ($role == 'guru')
            <li>
                <a href="{{ route('nilai.index') }}" class="sidebar-item">
                    @include('components.icons.nilai')
                    <span>Input Nilai</span>
                </a>
            </li>

            <li>
                <a href="{{ route('jadwal.index') }}" class="sidebar-item">
                    @include('components.icons.jadwal')
                    <span>Kelola Jadwal</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tugas.index') }}" class="sidebar-item">
                    @include('components.icons.file-upload')
                    <span>Tugas</span>
                </a>
            </li>
        @endif


        {{-- ===========================
            MENU SISWA
        ============================ --}}
        @if ($role == 'siswa')
            <li>
                <a href="{{ route('nilai.siswa') }}" class="sidebar-item">
                    @include('components.icons.nilai')
                    <span>Nilai Saya</span>
                </a>
            </li>

            <li>
                <a href="{{ route('jadwal.siswa') }}" class="sidebar-item">
                    @include('components.icons.jadwal')
                    <span>Jadwal Pelajaran</span>
                </a>
            </li>

            <li>
                <a href="{{ route('tugas.index') }}" class="sidebar-item">
                    @include('components.icons.file-upload')
                    <span>Tugas</span>
                </a>
            </li>
        @endif


        {{-- ===========================
            MENU ORANG TUA
        ============================ --}}
        @if ($role == 'orang_tua')
            <li>
                <a href="{{ route('parent.dashboard') }}" class="sidebar-item">
                    @include('components.icons.profil')
                    <span>Monitoring Anak</span>
                </a>
            </li>
        @endif


        {{-- ===========================
            MENU CHAT
        ============================ --}}
        <li>
            <a href="{{ route('chat.index') }}" class="sidebar-item">
                @include('components.icons.pesan')
                <span>Pesan</span>
            </a>
        </li>


        {{-- ===========================
            LOGOUT (POST + CSRF) — FIXED
        ============================ --}}
        <li class="pt-10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center space-x-3 text-red-600 hover:text-red-800 w-full text-left">
                    @include('components.icons.logout')
                    <span>Logout</span>
                </button>
            </form>
        </li>

    </ul>
</div>
