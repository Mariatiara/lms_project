@extends('layouts.dashboard')

@section('title', 'Detail Tugas - ' . $assignment->title)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('teacher.courses.show', $assignment->course_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Kursus
        </a>
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $assignment->title }}</h1>
                <p class="text-gray-500 mt-2">{{ $assignment->course->subject->name }} &bull; {{ $assignment->course->classroom->name }}</p>
                <div class="mt-2 text-sm text-gray-600">
                    <span class="font-semibold">Due Date:</span> {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Daftar Pengumpulan Siswa</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 text-sm uppercase">
                    <tr>
                        <th class="p-4 border-b">Nama Siswa</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">File</th>
                        <th class="p-4 border-b">Waktu Pengumpulan</th>
                        <th class="p-4 border-b w-1/3">Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        @php
                            $submission = $submissions->get($student->id);
                            $isSubmitted = $submission !== null;
                            $isLate = $isSubmitted && $submission->submitted_at && $submission->submitted_at->gt($assignment->due_date);
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="p-4">
                                <div class="font-medium text-gray-900">{{ $student->user->name ?? $student->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $student->nis }}</div>
                            </td>
                            <td class="p-4">
                                @if($isSubmitted)
                                    @if($isLate)
                                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold">Terlambat</span>
                                    @else
                                        <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Terkumpul</span>
                                    @endif
                                @else
                                    <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-xs font-bold">Belum</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($isSubmitted && $submission->file_path)
                                    <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline text-sm flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        Lihat File
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-600">
                                {{ $isSubmitted ? $submission->submitted_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="p-4">
                                <form action="{{ route('teacher.assignments.grade', $assignment->id) }}" method="POST" class="flex flex-col gap-2">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    <div class="flex gap-2">
                                        <input type="number" name="score" value="{{ $submission->score ?? '' }}" placeholder="Nilai (0-100)" min="0" max="100" step="0.01" class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            Simpan
                                        </button>
                                    </div>
                                    <textarea name="feedback" rows="2" placeholder="Berikan feedback..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">{{ $submission->feedback ?? '' }}</textarea>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-500">Tidak ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
