@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dinas.reports.incoming') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Laporan Masuk
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            <span class="ml-1 text-sm font-medium text-slate-800 md:ml-2">Detail Laporan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">{{ $report->title }}</h1>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <span class="font-medium text-indigo-600">{{ $report->school->name }}</span>
                <span class="text-slate-300">•</span>
                <span>{{ $report->report_period ? $report->report_period->translatedFormat('F Y') : 'Periode Tidak Spesifik' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            @php
                 $statusStyles = [
                    'submitted' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'reviewed' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'accepted' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'rejected' => 'bg-red-100 text-red-800 border-red-200',
                    'revision_needed' => 'bg-amber-100 text-amber-800 border-amber-200',
                ];
                $style = $statusStyles[$report->status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
            @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold border {{ $style }}">
                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        
        {{-- Left Column: Document Viewer & Details (8 cols) --}}
        <div class="md:col-span-8 space-y-8">
            
            {{-- Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                    </div>
                    <div class="space-y-4 flex-1">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wide">Deskripsi Laporan</h3>
                            <p class="mt-2 text-slate-600 leading-relaxed">{{ $report->description ?? 'Tidak ada deskripsi yang disertakan.' }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-slate-500 pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                {{ $report->uploader->name }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $report->created_at->format('d F Y, H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Document Preview --}}
            <div class="bg-slate-900 rounded-2xl shadow-lg overflow-hidden border border-slate-700 ribbon-container group">
                <div class="bg-slate-800 px-4 py-3 flex items-center justify-between border-b border-slate-700">
                    <div class="flex items-center gap-3">
                        <span class="text-slate-300 text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            {{ basename($report->file_path) }}
                        </span>
                    </div>
                    <div>
                        <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            Buka di Tab Baru
                        </a>
                    </div>
                </div>
                <div class="relative bg-slate-100 flex items-center justify-center min-h-[500px]">
                     @if(Str::endsWith(strtolower($report->file_path), '.pdf'))
                        <iframe src="{{ asset('storage/' . $report->file_path) }}" class="w-full h-[800px]" frameborder="0"></iframe>
                    @else
                        <div class="text-center p-12">
                            <div class="w-20 h-20 bg-slate-200 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700">Pratinjau Tidak Tersedia</h3>
                            <p class="text-slate-500 mb-6">Format file ini tidak mendukung pratinjau langsung.</p>
                            <a href="{{ asset('storage/' . $report->file_path) }}" class="inline-flex items-center px-6 py-3 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 transition-colors shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh File
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right Column: Actions & History (4 cols) --}}
        <div class="md:col-span-4 space-y-6">
            
            {{-- Validation Panel --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden top-24">
                
                {{-- Revision Alert inside Panel --}}
                @if($report->histories->count() > 0 && $report->status == 'submitted')
                <div class="bg-blue-50/50 border-b border-blue-100 p-4">
                    <div class="flex gap-3">
                        <div class="shrink-0 mt-1">
                            <div class="w-2 h-2 rounded-full bg-blue-500 ring-4 ring-blue-100"></div>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-blue-900">Revisi Terbaru Masuk</h4>
                            <p class="text-xs text-blue-700 mt-1 leading-relaxed">
                                Sekolah telah mengunggah revisi ke-{{ $report->histories->count() }}. Silakan tinjau kembali dokumen yang diperbarui.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Validasi Laporan
                    </h3>
                    
                    @if($report->status == 'accepted')
                        <div class="bg-emerald-50 rounded-xl p-5 border border-emerald-100 text-center">
                            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h4 class="font-bold text-emerald-900 text-lg">Laporan Diterima</h4>
                            <p class="text-sm text-emerald-700 mt-2 mb-4">Laporan ini telah divalidasi dan diterima.</p>
                            
                            @if($report->dinas_feedback)
                            <div class="text-left bg-emerald-100/50 p-3 rounded-lg text-sm text-emerald-800 italic">
                                "{{ $report->dinas_feedback }}"
                            </div>
                            @endif
                            
                            <div class="mt-4 pt-4 border-t border-emerald-100/50 text-xs text-emerald-600">
                                Direview oleh {{ $report->reviewer?->name ?? 'Admin' }}<br>
                                {{ $report->reviewed_at?->format('d M Y, H:i') }}
                            </div>
                        </div>
                    @else
                        <form action="{{ route('dinas.reports.review', $report->id) }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Keputusan Validasi</label>
                                <div class="relative">
                                    <select name="status" class="w-full pl-4 pr-10 py-2.5 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition appearance-none">
                                        <option value="accepted">Terima Laporan</option>
                                        <option value="revision_needed">Kembalikan (Perlu Revisi)</option>
                                        <option value="rejected">Tolak Laporan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Feedback</label>
                                <textarea name="dinas_feedback" rows="4" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition placeholder:text-slate-400" placeholder="Berikan alasan atau detail revisi yang diperlukan..."></textarea>
                            </div>

                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 hover:shadow-indigo-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                                Kirim Keputusan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- History Timeline --}}
            @if($report->histories->count() > 0)
            <div class="pt-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 px-1">Riwayat Versi</h3>
                <div class="relative pl-4 border-l-2 border-slate-200 space-y-8 ml-2">
                    @foreach($report->histories as $history)
                    <div class="relative">
                        <!-- Dot -->
                        <div class="absolute -left-[21px] top-1.5 w-3 h-3 rounded-full border-2 border-white {{ $history->status == 'revision_needed' ? 'bg-amber-400' : 'bg-red-400' }}"></div>
                        
                        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-bold text-slate-500">Versi {{ $report->histories->count() - $loop->index }}</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                    {{ $history->status == 'revision_needed' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700' }}">
                                    {{ $history->status == 'revision_needed' ? 'REVISI' : 'DITOLAK' }}
                                </span>
                            </div>
                            
                            @if($history->dinas_feedback)
                                <p class="text-xs text-slate-600 italic bg-slate-50 p-2 rounded border border-slate-100 mb-3">
                                    "{{ $history->dinas_feedback }}"
                                </p>
                            @endif

                            <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                                <span class="text-xs text-slate-400">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                <a href="{{ asset('storage/' . $history->file_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-700 text-xs font-semibold flex items-center hover:underline">
                                    Lihat File
                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
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
