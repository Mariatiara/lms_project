@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Kepala Sekolah</h1>
            <p class="text-slate-500 mt-1">Selamat datang kembali, {{ Auth::user()->name }}</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-sm font-medium">
                {{ \Carbon\Carbon::now()->format('d F Y') }}
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Students --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Siswa</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $stats['student_count'] }}</div>
            <div class="mt-2 text-sm text-slate-500">
                Siswa Aktif
            </div>
        </div>

        {{-- Teachers --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Guru</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $stats['teacher_count'] }}</div>
            <div class="mt-2 text-sm text-slate-500">
                Tenaga Pengajar
            </div>
        </div>

        {{-- Classrooms --}}
        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-violet-50 rounded-lg flex items-center justify-center text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Total Kelas</span>
            </div>
            <div class="text-3xl font-bold text-slate-800">{{ $stats['classroom_count'] }}</div>
            <div class="mt-2 text-sm text-slate-500">
                Rombongan Belajar
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Recent Reports --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-bold text-slate-800">Laporan Terbaru</h2>
                <a href="{{ route('school-reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Judul Laporan</th>
                            <th class="px-6 py-3 font-medium">Tipe</th>
                            <th class="px-6 py-3 font-medium">Tanggal</th>
                            <th class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestReports as $report)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $report->title }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ ucfirst($report->report_type) }}
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $report->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($report->status === 'submitted')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        Submitted
                                    </span>
                                @elseif($report->status === 'reviewed')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Reviewed
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                Belum ada laporan yang dibuat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Upcoming Events --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="font-bold text-slate-800">Agenda Mendatang</h2>
            </div>
            <div class="p-6 space-y-6">
                @forelse($upcomingEvents as $event)
                <div class="flex items-start gap-4">
                    <div class="shrink-0 w-12 text-center">
                        <div class="text-xs font-bold text-indigo-600 uppercase">{{ $event->start_date->format('M') }}</div>
                        <div class="text-xl font-bold text-slate-800">{{ $event->start_date->format('d') }}</div>
                    </div>
                    <div>
                        <h3 class="font-medium text-slate-800">{{ $event->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1">
                            {{ $event->start_date->format('H:i') }} - {{ $event->end_date->format('H:i') }}
                        </p>
                        @if($event->is_holiday)
                            <span class="inline-block mt-2 px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600">
                                Libur
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-500 py-4">
                    Tidak ada agenda terdekat.
                </div>
                @endforelse
            </div>
             <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                <a href="{{ route('academic.calendar.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    Lihat Kalender Lengkap &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
