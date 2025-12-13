@extends('layouts.dashboard')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <!-- Header Card -->
    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
        <div class="flex flex-col md:flex-row justify-between items-start gap-6">
            <div class="space-y-4 flex-1">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('teacher.courses.show', $tugas->course->id) }}" class="hover:text-blue-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Kelas
                    </a>
                    <span>/</span>
                    <span>Detail Tugas</span>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900">{{ $tugas->title }}</h1>
                
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700">
                        {{ $tugas->course->subject->name }}
                    </span>
                     <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                        {{ $tugas->course->classroom->name }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $tugas->due_date->isPast() ? 'bg-red-50 text-red-700' : 'bg-yellow-50 text-yellow-700' }}">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Deadline: {{ $tugas->due_date ? $tugas->due_date->format('d M Y, H:i') : '-' }}
                    </span>
                </div>
            </div>
            
            @if(auth()->user()->role == \App\Enums\UserRole::GURU)
            <div class="flex gap-3">
                 <!-- Actions for Teacher could go here if needed independently -->
            </div>
            @endif
        </div>

        <div class="mt-8 pt-8 border-t border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Tugas</h3>
            <div class="prose prose-blue max-w-none text-gray-600">
                {{ $tugas->description }}
            </div>
        </div>
    </div>

    {{-- SISWA UPLOAD SECTION --}}
    @if(auth()->user()->role == \App\Enums\UserRole::SISWA)
    <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm max-w-2xl">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Pengumpulan Tugas</h3>
        
        {{-- TODO: Check if submission exists and show status/score if graded --}}
        
        <form action="{{ route('assignments.upload', $tugas->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File Jawaban</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-400 transition-colors bg-gray-50">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                <span>Upload a file</span>
                                <input id="file-upload" name="file" type="file" class="sr-only">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOCX, PPTX up to 10MB</p>
                    </div>
                </div>
            </div>

            <button class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                Kirim Tugas
            </button>
        </form>
    </div>
    @endif

    {{-- GURU GRADING SECTION --}}
    @if(auth()->user()->role == \App\Enums\UserRole::GURU)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Status Pengumpulan Siswa</h3>
            <div class="text-sm text-gray-500">
                Total Siswa: <span class="font-semibold text-gray-900">{{ $studentStatuses->count() }}</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="p-6">Siswa</th>
                        <th class="p-6 text-center">Status</th>
                        <th class="p-6">File Jawaban</th>
                        <th class="p-6">Penilaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($studentStatuses as $status)
                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <td class="p-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-linear-to-br from-blue-100 to-blue-200 text-blue-700 flex items-center justify-center font-bold text-xs shadow-sm">
                                    {{ substr($status['student']->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $status['student']->user->name }}</p>
                                    <p class="text-xs text-gray-500 font-mono">{{ $status['student']->nis }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6 text-center">
                            @if($status['is_submitted'])
                                <span class="inline-flex flex-col items-center">
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold shadow-sm border border-green-200">
                                        Sudah Mengumpulkan
                                    </span>
                                    <span class="text-[10px] text-gray-400 mt-1 font-medium">
                                        {{ $status['submission']->created_at->format('d M H:i') }}
                                    </span>
                                </span>
                            @else
                                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-xs font-bold border border-red-100">
                                    Belum Mengumpulkan
                                </span>
                            @endif
                        </td>
                        <td class="p-6">
                            @if($status['is_submitted'])
                                <a href="{{ asset('storage/' . $status['submission']->file_path) }}" target="_blank" class="group flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                                    <div class="p-2 bg-gray-100 rounded-lg group-hover:bg-blue-50 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <span class="font-medium underline decoration-gray-300 group-hover:decoration-blue-400 underline-offset-2">Download File</span>
                                </a>
                            @else
                                <span class="text-gray-400 text-sm italic flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    Tidak ada file
                                </span>
                            @endif
                        </td>
                        <td class="p-6">
                            @if($status['is_submitted'])
                                <form action="{{ route('pengumpulan.nilai', $status['submission']->id) }}" method="POST" class="space-y-2">
                                    @csrf
                                    <div class="flex items-center gap-2">
                                        <div class="relative rounded-md shadow-sm">
                                            <input type="number" name="nilai" placeholder="0" value="{{ $status['submission']->score ?? '' }}" required
                                                   class="block w-20 rounded-lg border-gray-300 pl-3 pr-8 focus:border-blue-500 focus:ring-blue-500 sm:text-sm" min="0" max="100">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <span class="text-gray-400 sm:text-xs">/100</span>
                                            </div>
                                        </div>
                                        <button class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow-sm transition-colors" title="Simpan Nilai">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </div>
                                    <input type="text" name="catatan" placeholder="Beri catatan feedback..." value="{{ $status['submission']->feedback ?? '' }}"
                                           class="block w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-gray-400">
                                </form>
                            @else
                                <span class="text-xs text-gray-400 font-medium bg-gray-50 px-2 py-1 rounded border border-gray-100">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
