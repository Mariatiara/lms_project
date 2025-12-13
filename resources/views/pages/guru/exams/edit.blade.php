@extends('layouts.dashboard')

@section('title', 'Edit Ujian - ' . $exam->title)

@section('content')
<div class="p-6">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('teacher.exams.index', $exam->course_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Kembali ke Daftar Ujian
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Ujian</h1>
            <p class="text-gray-500 mt-1">{{ $exam->course->subject->name }} &bull; {{ $exam->course->classroom->name }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ route('teacher.exams.update', $exam->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                        <div class="flex items-center gap-2 text-red-700 font-bold mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Terjadi Kesalahan!</span>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Ujian</label>
                    <input type="text" name="title" value="{{ old('title', $exam->title) }}" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Ujian</label>
                    <div class="relative">
                        <select name="category" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border appearance-none">
                            @foreach(\App\Enums\ExamCategory::cases() as $category)
                                <option value="{{ $category->value }}" {{ old('category', $exam->category->value) == $category->value ? 'selected' : '' }}>{{ $category->label() }}</option>
                            @endforeach
                        </select>
                         <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Mulai</label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time', $exam->start_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Selesai</label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time', $exam->end_time->format('Y-m-d\TH:i')) }}" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Durasi Pengerjaan (Menit)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" min="1" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $exam->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 h-5 w-5">
                    <div>
                        <label for="is_published" class="font-medium text-gray-800">Publikasikan Ujian</label>
                        <p class="text-xs text-gray-500">Jika dicentang, siswa dapat melihat dan mengerjakan ujian sesuai waktu yang ditentukan.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-blue-500/30 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
