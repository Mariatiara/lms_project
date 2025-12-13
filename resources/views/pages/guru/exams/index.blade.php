@extends('layouts.dashboard')

@section('title', 'Ujian & Kuis - ' . $course->subject->name)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ route('teacher.courses.show', $course->id) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Kursus
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Ujian & Kuis</h1>
                <p class="text-gray-500 mt-1">{{ $course->subject->name }} &bull; {{ $course->classroom->name }}</p>
            </div>
            <a href="{{ route('teacher.exams.create', $course->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Buat Ujian Baru
            </a>
        </div>
    </div>

    @if($exams->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Belum ada ujian</h3>
            <p class="text-gray-500">Buat ujian pertama anda untuk memulai evaluasi siswa.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($exams as $exam)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($exam->is_published)
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold">Terbit</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Draft</span>
                            @endif
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                    </svg>
                                </button>
                                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-10" style="display: none;">
                                    <a href="{{ route('teacher.exams.edit', $exam->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Edit Pengaturan</a>
                                    <form action="{{ route('teacher.exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('Hapus ujian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $exam->title }}</h3>
                    <div class="space-y-2 mb-6 flex-1">
                         <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $exam->duration_minutes }} Menit</span>
                        </div>
                         <div class="flex items-center gap-2 text-sm text-gray-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            <span>{{ $exam->questions->count() }} Soal</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-auto">
                        <a href="{{ route('teacher.exams.questions', $exam->id) }}" class="flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors">
                            Kelola Soal
                        </a>
                        <!-- Logic for results or monitoring could go here -->
                        <a href="{{ route('teacher.exams.results', $exam->id) }}" class="flex justify-center items-center px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-medium transition-colors">
                            Hasil & Koreksi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
