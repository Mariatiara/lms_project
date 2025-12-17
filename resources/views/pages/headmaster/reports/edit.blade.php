@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back --}}
    <a href="{{ route('school-reports.show', $schoolReport->id) }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Detail
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-amber-50/50">
            <h1 class="text-xl font-bold text-slate-800">Perbaiki Laporan</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui data atau upload file revisi untuk laporan ini.</p>
        </div>

        <form action="{{ route('school-reports.update', $schoolReport->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="space-y-8">
                 {{-- Validasi Error --}}
                @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 border border-red-100">
                    <ul class="text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Judul Laporan --}}
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">
                            Judul Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                               value="{{ old('title', $schoolReport->title) }}" required>
                    </div>

                    {{-- Tipe Laporan --}}
                    <div>
                        <label for="report_type" class="block text-sm font-semibold text-slate-700 mb-2">
                            Tipe Laporan <span class="text-red-500">*</span>
                        </label>
                        <select name="report_type" id="report_type" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" required>
                             @foreach(['Bulanan', 'Semester', 'Tahunan', 'Keuangan', 'Insidental'] as $type)
                                <option value="{{ $type }}" {{ old('report_type', $schoolReport->report_type) == $type ? 'selected' : '' }}>
                                    Laporan {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Periode Laporan --}}
                    <div>
                        <label for="report_period" class="block text-sm font-semibold text-slate-700 mb-2">
                            Periode Laporan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="report_period" id="report_period" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition" 
                               value="{{ old('report_period', $schoolReport->report_period ? $schoolReport->report_period->format('Y-m-d') : '') }}" required>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">
                        Deskripsi / Catatan Tambahan
                    </label>
                    <textarea name="description" id="description" rows="4" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm transition">{{ old('description', $schoolReport->description) }}</textarea>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        File Revisi (Opsional jika tidak diganti)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-slate-300 rounded-xl hover:bg-slate-50 hover:border-indigo-400 transition-colors group cursor-pointer relative">
                        <input type="file" name="file" id="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <span class="font-medium text-indigo-600 hover:text-indigo-500">Upload file baru</span>
                                <p class="pl-1">untuk merevisi</p>
                            </div>
                            <p class="text-xs text-slate-500">
                                Biarkan kosong jika menggunakan file lama: 
                                <span class="font-medium text-slate-700">{{ basename($schoolReport->file_path) }}</span>
                            </p>
                        </div>
                    </div>
                    <div id="file-name-display" class="mt-2 text-sm text-emerald-600 font-medium" style="display: none;">
                        File baru terpilih: <span id="file-name-text"></span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-4 mt-8 pt-6 border-t border-slate-100">
                <a href="{{ route('school-reports.show', $schoolReport->id) }}" class="px-6 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Simpan & Kirim Ulang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('file').addEventListener('change', function(e) {
        var fileName = e.target.files[0] ? e.target.files[0].name : '';
        var displayDiv = document.getElementById('file-name-display');
        var displayText = document.getElementById('file-name-text');
        
        if(fileName) {
            displayText.textContent = fileName;
            displayDiv.style.display = 'block';
        } else {
            displayDiv.style.display = 'none';
        }
    });
</script>
@endsection
