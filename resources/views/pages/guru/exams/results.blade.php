@extends('layouts.dashboard')

@section('title', 'Hasil Ujian - ' . $exam->title)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('teacher.exams.index', $exam->course_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Ujian
        </a>
        <h1 class="text-3xl font-bold text-gray-900">Hasil Ujian: {{ $exam->title }}</h1>
        <p class="text-gray-500 mt-1">{{ $exam->course->classroom->name }} &bull; {{ $exam->attempts->count() }} Siswa Mengumpulkan</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-bold">
                    <th class="px-6 py-4">Nama Siswa</th>
                    <th class="px-6 py-4">Waktu Mulai</th>
                    <th class="px-6 py-4">Waktu Selesai</th>
                    <th class="px-6 py-4">Nilai</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($exam->attempts as $attempt)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $attempt->student->user->name }}
                            <div class="text-xs text-gray-500">{{ $attempt->student->nis }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $attempt->started_at->format('d M H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $attempt->finished_at ? $attempt->finished_at->format('d M H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($attempt->total_score >= $exam->course->subject->passing_grade)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded font-bold text-sm">{{ number_format($attempt->total_score, 2) }}</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded font-bold text-sm">{{ number_format($attempt->total_score, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('teacher.exams.review', ['id' => $exam->id, 'attemptId' => $attempt->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm flex items-center gap-1 justify-end">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Periksa / Koreksi
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Belum ada siswa yang mengerjakan ujian ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
