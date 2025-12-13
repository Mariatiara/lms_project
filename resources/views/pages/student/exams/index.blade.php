@extends('layouts.dashboard')

@section('title', 'Daftar Ujian')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Ujian & Kuis</h1>
        <p class="text-gray-500 mt-1">Daftar ujian yang tersedia untuk kelas anda.</p>
    </div>

    @if($exams->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
             <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Tidak ada ujian aktif</h3>
            <p class="text-gray-500">Saat ini belum ada jadwal ujian untuk kelas anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($exams as $exam)
                @php
                    $now = now();
                    $isAvailable = $now->between($exam->start_time, $exam->end_time);
                    $isFinished = $now->gt($exam->end_time);
                    $isUpcoming = $now->lt($exam->start_time);
                @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        
                        @if($isAvailable)
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold animate-pulse">Berlangsung</span>
                        @elseif($isFinished)
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold">Selesai</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold">Akan Datang</span>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $exam->title }}</h3>
                    <p class="text-sm text-gray-500 font-medium mb-4">{{ $exam->course->subject->name }}</p>

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
                    </div>

                    <a href="{{ route('student.exams.show', $exam->id) }}" class="block w-full text-center px-4 py-2 rounded-lg font-medium transition-colors border {{ $isAvailable ? 'bg-blue-600 text-white hover:bg-blue-700 border-transparent shadow-lg shadow-blue-500/30' : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100' }}">
                        {{ $isFinished ? 'Lihat Hasil' : 'Lihat Detail' }}
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
