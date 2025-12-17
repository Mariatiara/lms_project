@extends('layouts.dashboard')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Breadcrumb / Back --}}
    <a href="{{ route('school-reports.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Laporan
    </a>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Header / Banner --}}
        <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100 flex flex-col md:flex-row justify-between md:items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 leading-tight mb-2">{{ $schoolReport->title }}</h1>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ ucfirst($schoolReport->report_type) }}
                    </span>
                    <span class="text-slate-400 text-xs">•</span>
                    <span class="text-sm text-slate-500">
                        Diunggah pada {{ $schoolReport->created_at->translatedFormat('d F Y, H:i') }}
                    </span>
                </div>
            </div>
            
            <div class="shrink-0">
                @php
                    $statusStyles = [
                        'submitted' => 'bg-blue-100 text-blue-800 ring-blue-600/20',
                        'reviewed' => 'bg-purple-100 text-purple-800 ring-purple-600/20',
                        'accepted' => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
                        'rejected' => 'bg-red-100 text-red-800 ring-red-600/20',
                        'revision_needed' => 'bg-amber-100 text-amber-800 ring-amber-600/20',
                    ];
                    $style = $statusStyles[$schoolReport->status] ?? 'bg-slate-100 text-slate-800 ring-slate-600/20';
                    
                    $statusLabel = [
                        'submitted' => 'Terkirim',
                        'reviewed' => 'Ditinjau',
                        'accepted' => 'Diterima',
                        'rejected' => 'Ditolak',
                        'revision_needed' => 'Perlu Revisi',
                    ][$schoolReport->status] ?? $schoolReport->status;
                @endphp
                <span class="inline-flex items-center rounded-md px-3 py-1 text-sm font-medium ring-1 ring-inset {{ $style }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>

        <div class="p-8 space-y-8">
            {{-- Main Details Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Periode Laporan</h3>
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $schoolReport->report_period ? $schoolReport->report_period->translatedFormat('F Y') : '-' }}
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pengunggah</h3>
                    <div class="flex items-center gap-2 text-slate-700 font-medium">
                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs text-slate-500 font-bold">
                            {{ substr($schoolReport->uploader->name ?? 'U', 0, 1) }}
                        </div>
                        {{ $schoolReport->uploader->name ?? 'User' }}
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($schoolReport->description)
            <div>
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Deskripsi / Catatan</h3>
                <div class="bg-slate-50 rounded-xl p-4 text-slate-700 text-sm leading-relaxed border border-slate-100">
                    {{ $schoolReport->description }}
                </div>
            </div>
            @endif

            {{-- Feedback Section --}}
            @if($schoolReport->dinas_feedback)
            <div class="bg-amber-50 rounded-xl border border-amber-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-amber-100 bg-amber-100/50 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <h3 class="font-semibold text-amber-800">Catatan dari Dinas</h3>
                </div>
                <div class="p-6 text-amber-900 text-sm leading-relaxed">
                    {{ $schoolReport->dinas_feedback }}
                </div>
                @if($schoolReport->reviewed_by)
                <div class="px-6 py-3 bg-amber-100/20 border-t border-amber-100 text-xs text-amber-700 flex justify-end">
                    Direview oleh: {{ $schoolReport->reviewer->name ?? 'Admin Dinas' }} pada {{ $schoolReport->reviewed_at ? $schoolReport->reviewed_at->format('d/m/Y H:i') : '-' }}
                </div>
                @endif
            </div>
            @endif

            {{-- File Viewer / Download --}}
            <div>
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">File Dokumen</h3>
                <div class="flex items-center gap-4 p-4 border border-slate-200 rounded-xl hover:border-indigo-300 transition-colors group bg-white">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center shrink-0 group-hover:bg-indigo-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-900 truncate">{{ basename($schoolReport->file_path) }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">Klik tombol di samping untuk mengunduh</p>
                    </div>
                <div class="flex items-center gap-3">
                    <a href="{{ Storage::url($schoolReport->file_path) }}" target="_blank" 
                       class="inline-flex items-center px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download
                    </a>

                    @if(in_array($schoolReport->status, ['revision_needed', 'rejected', 'submitted']))
                    <a href="{{ route('school-reports.edit', $schoolReport->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition shadow-lg shadow-amber-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        {{ $schoolReport->status === 'submitted' ? 'Edit Laporan' : 'Perbaiki Laporan' }}
                    </a>
                    @endif
                </div>
                </div>
            </div>
            {{-- End of File Viewer --}}

            {{-- History Section --}}
            @if($schoolReport->histories->count() > 0)
            <div class="mt-8 pt-8 border-t border-slate-100">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Riwayat Versi Sebelumnya</h3>
                <div class="space-y-4">
                    @foreach($schoolReport->histories as $history)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center text-slate-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-medium text-slate-700">Versi {{ $schoolReport->histories->count() - $loop->index }}</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 border border-slate-300">
                                        {{ $history->status }}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-500 mt-0.5">Diarsipkan pada {{ $history->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                             @if($history->dinas_feedback)
                             <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-xs text-amber-600 hover:text-amber-700 underline mr-2">
                                    Lihat Feedback
                                </button>
                                <div x-show="open" @click.outside="open = false" class="absolute right-0 bottom-full mb-2 w-64 p-3 bg-white border border-slate-200 shadow-xl rounded-lg z-10 text-xs text-slate-700">
                                    <strong>Feedback:</strong><br>
                                    {{ $history->dinas_feedback }}
                                </div>
                             </div>
                             @endif

                             <a href="{{ Storage::url($history->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                Download
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
