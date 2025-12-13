@extends('layouts.dashboard')

@section('title', $assignment->title)

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $assignment->title }}</h1>
        <p class="text-gray-500 mt-2">{{ $assignment->course->subject->name }} &bull; {{ $assignment->course->teacher->user->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Deskripsi Tugas</h3>
                <div class="prose max-w-none text-gray-600">
                    {!! nl2br(e($assignment->description)) !!}
                </div>
            </div>
        </div>

        {{-- Right Column: Submission --}}
        <div class="space-y-6">
             {{-- Info Card --}}
             <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Tenggat Waktu</p>
                        <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @if($submission)
                            @if(\Carbon\Carbon::parse($submission->submitted_at)->gt($assignment->due_date))
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-700">
                                    Sudah Dikumpulkan (Terlambat {{ \Carbon\Carbon::parse($submission->submitted_at)->diffForHumans(\Carbon\Carbon::parse($assignment->due_date), ['parts' => 2, 'join' => ', ', 'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE]) }})
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-700">Sudah Dikumpulkan</span>
                            @endif
                        @else
                            @if(\Carbon\Carbon::now()->gt($assignment->due_date))
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-700">Terlambat</span>
                            @else
                                <span class="inline-block px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-700">Belum Dikumpulkan</span>
                            @endif
                        @endif
                    </div>
                    @if($submission && $submission->score)
                        <div>
                            <p class="text-sm text-gray-500">Nilai</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $submission->score }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Submission Form --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pengumpulan</h3>
                
                @if($submission)
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
                        <p class="text-sm text-blue-800 font-medium mb-1">File Anda:</p>
                        <a href="{{ asset('storage/' . $submission->file_path) }}" target="_blank" class="text-blue-600 underline text-sm break-all">
                            {{ basename($submission->file_path) }}
                        </a>
                        <p class="text-xs text-blue-500 mt-2">Dikumpulkan pada: {{ $submission->submitted_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    @if(!$submission->score)
                        <p class="text-sm text-gray-500 italic text-center">Anda sudah mengumpulkan tugas ini. Menunggu penilaian guru.</p>
                        {{-- Optional: Allow resubmission if needed --}}
                        <div class="mt-4 pt-4 border-t border-gray-100">
                             <p class="text-xs text-gray-400 mb-2 text-center">Ingin mengumpulkan ulang?</p>
                             <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                                </div>
                                <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-sm">
                                    Kirim Ulang
                                </button>
                            </form>
                        </div>
                    @endif

                @else
                    <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Tugas</label>
                            <input type="file" name="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                            <p class="text-xs text-gray-400 mt-1">Format: PDF, DOC, DOCX, ZIP, IMAGES. Max: 10MB</p>
                        </div>
                        <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Kirim Tugas
                        </button>
                    </form>
                @endif
            </div>

            @if($submission && $submission->feedback)
                <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-6">
                    <h3 class="text-md font-bold text-yellow-800 mb-2">Feedback Guru</h3>
                    <p class="text-sm text-yellow-700">{{ $submission->feedback }}</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
