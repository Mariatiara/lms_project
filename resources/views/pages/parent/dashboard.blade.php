@extends('layouts.dashboard')

@section('content')

@if(session('success'))
<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Sukses!</strong>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Error!</strong>
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif

<div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}</h1>
        <p class="text-gray-600">Pantau perkembangan akademik anak Anda di sini.</p>
    </div>

    {{-- Child Switcher --}}
    <div class="mt-4 md:mt-0 flex space-x-2 overflow-x-auto pb-2">
        @foreach($children as $child)
            <form action="{{ route('parent.switch-child', $child->id) }}" method="POST">
                @csrf
                <button type="submit" 
                    class="px-4 py-2 rounded-full border transition-colors flex items-center space-x-2 whitespace-nowrap
                    {{ $activeChild->id == $child->id ? 'bg-indigo-600 text-white border-indigo-600 shadow-md' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold {{ $activeChild->id == $child->id ? 'text-indigo-800' : 'text-gray-500' }}">
                        {{ substr($child->nama, 0, 1) }}
                    </div>
                    <span>{{ explode(' ', $child->nama)[0] }}</span> 
                    {{-- Display First Name only for brevity --}}
                </button>
            </form>
        @endforeach
    </div>
</div>

{{-- Active Child Profile Header --}}
<div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl font-bold">
                {{ substr($activeChild->nama, 0, 1) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $activeChild->nama }}</h2>
                <div class="flex items-center gap-2 text-gray-500 text-sm mt-1">
                    <span class="px-2 py-0.5 bg-gray-100 rounded text-xs font-medium border border-gray-200">
                        {{ $activeChild->classroom->name ?? 'Belum ada kelas' }}
                    </span>
                    <span>•</span>
                    <span>NIS: {{ $activeChild->nis }}</span>
                </div>
            </div>
        </div>
        <div>
             {{-- Link to Detail Profile if exists --}}
             {{-- <a href="#" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">Lihat Profil Lengkap &rarr;</a> --}}
        </div>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    {{-- Attendance Card --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Kehadiran Hari Ini</h3>
            <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                @include('components.icons.jadwal') 
            </div>
        </div>
        @if($attendanceToday)
            <div class="flex items-center gap-3">
                @php
                    $statusColors = [
                        'present' => 'bg-green-100 text-green-700 border-green-200',
                        'late' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        'absent' => 'bg-red-100 text-red-700 border-red-200',
                        'sick' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'permission' => 'bg-purple-100 text-purple-700 border-purple-200',
                    ];
                    $statusLabel = [
                        'present' => 'Hadir',
                        'late' => 'Terlambat',
                        'absent' => 'Alpha',
                        'sick' => 'Sakit',
                        'permission' => 'Izin',
                    ];
                    $colorClass = $statusColors[$attendanceToday->status] ?? 'bg-gray-100 text-gray-700';
                    $labelText = $statusLabel[$attendanceToday->status] ?? ucfirst($attendanceToday->status);
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-bold border {{ $colorClass }}">
                    {{ $labelText }}
                </span>
                <span class="text-gray-500 text-sm">{{ \Carbon\Carbon::parse($attendanceToday->time)->format('H:i') }}</span>
            </div>
        @else
            <div class="flex items-center gap-2 text-gray-500">
                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                <span>Belum ada data absensi hari ini</span>
            </div>
        @endif
    </div>

    {{-- Assignments Card --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Tugas Belum Selesai</h3>
            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                @include('components.icons.file-upload')
            </div>
        </div>
        <div class="flex items-baseline gap-1">
            <span class="text-3xl font-bold text-gray-800">{{ $pendingAssignmentsCount }}</span>
            <span class="text-sm text-gray-500">tugas</span>
        </div>
        <p class="text-xs text-gray-500 mt-2">Perlu diselesaikan segera</p>
    </div>

    {{-- Next Exam --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-700">Ujian Berikutnya</h3>
            <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        @if($nextExam)
            <div>
                <h4 class="font-bold text-gray-800 text-sm truncate">{{ $nextExam->title }}</h4>
                <p class="text-sm text-gray-600">{{ $nextExam->course->subject->name ?? 'Mapel Unknown' }}</p>
                <div class="mt-2 flex items-center gap-2 text-xs font-medium text-purple-700 bg-purple-50 px-2 py-1 rounded w-fit">
                    <span>{{ \Carbon\Carbon::parse($nextExam->start_time)->format('d M, H:i') }}</span>
                </div>
            </div>
        @else
            <p class="text-gray-500 text-sm">Tidak ada jadwal ujian dalam waktu dekat.</p>
        @endif
    </div>
</div>

{{-- Pending Assignments List --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg">Daftar Tugas Pending</h3>
        {{-- <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">Lihat Semua</a> --}}
    </div>
    
    @if($pendingAssignments->count() > 0)
        <table class="w-full text-left bg-white">
            <thead class="bg-gray-50 text-gray-500 font-semibold text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3">Judul Tugas</th>
                    <th class="px-6 py-3">Mata Pelajaran</th>
                    <th class="px-6 py-3">Tenggat Waktu</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($pendingAssignments as $task)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $task->title }}</td>
                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $task->course->subject->name ?? '-' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $isOverdue = \Carbon\Carbon::parse($task->due_date)->isPast();
                        @endphp
                        <span class="text-sm {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                            {{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }}
                        </span>
                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full font-bold">Pending</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="p-8 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 text-green-600 mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <p class="text-gray-800 font-medium">Hore! Semua tugas sudah dikerjakan.</p>
            <p class="text-gray-500 text-sm mt-1">Tidak ada tugas yang tertunda saat ini.</p>
        </div>
    @endif
</div>

@endsection
