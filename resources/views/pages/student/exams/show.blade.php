@extends('layouts.dashboard')

@section('title', $exam->title)

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('student.exams.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Ujian
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-8 bg-linear-to-r from-blue-600 to-indigo-700 text-white relative overflow-hidden">
            <div class="absolute inset-0 bg-white/10 pattern-grid-lg opacity-20"></div>
            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">{{ $exam->title }}</h1>
                <p class="text-blue-100 text-lg">{{ $exam->course->subject->name }}</p>
            </div>
        </div>

        <div class="p-8">
            <!-- Rules Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                 <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-1 uppercase tracking-active font-semibold">Waktu Pengerjaan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $exam->duration_minutes }} <span class="text-sm font-normal text-gray-500">Menit</span></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-1 uppercase tracking-active font-semibold">Jumlah Soal</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $exam->questions()->count() }} <span class="text-sm font-normal text-gray-500">Butir</span></p>
                </div>
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-1 uppercase tracking-active font-semibold">KKM / Passing Grade</p>
                    <p class="text-2xl font-bold text-gray-900">75 <span class="text-sm font-normal text-gray-500">Poin</span></p>
                </div>
            </div>

            <!-- Instructions -->
            <div class="prose max-w-none text-gray-600 mb-8">
                <h3 class="text-gray-900 font-bold">Petunjuk Pengerjaan:</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Berdoalah sebelum memulai ujian.</li>
                    <li>Waktu akan berjalan otomatis saat tombol "Mulai Ujian" ditekan.</li>
                    <li>Jawaban akan tersimpan otomatis saat anda berpindah soal atau menekan tombol simpan.</li>
                    <li>Dilarang membuka tab lain atau melakukan kecurangan selama ujian berlangsung.</li>
                    <li>Jika waktu habis, ujian akan otomatis disubmit dengan jawaban terakhir yang tersimpan.</li>
                </ul>
            </div>

            <!-- Action -->
            <div class="border-t border-gray-100 pt-6 flex justify-end">
                @if($existingAttempt && $existingAttempt->finished_at)
                    <a href="{{ route('student.exams.result', $exam->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-lg shadow-green-500/30 transition-all flex items-center gap-2">
                         Lihat Hasil
                    </a>
                @elseif($existingAttempt && !$existingAttempt->finished_at)
                     <a href="{{ route('student.exams.take', $exam->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-lg shadow-yellow-500/30 transition-all flex items-center gap-2">
                         Lanjutkan Ujian
                         <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    @php
                        $now = now();
                        $isAvailable = $now->between($exam->start_time, $exam->end_time);
                    @endphp
                    
                    @if($isAvailable)
                        <form action="{{ route('student.exams.start', $exam->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah anda yakin ingin memulai ujian sekarang? Waktu akan terus berjalan.')" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
                                Mulai Ujian Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <button disabled class="bg-gray-300 text-gray-500 px-8 py-3 rounded-xl font-bold text-lg cursor-not-allowed">
                            Ujian Belum Tersedia
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
