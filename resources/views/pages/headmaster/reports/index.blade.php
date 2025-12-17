@extends('layouts.dashboard')
@section('content')
<div class="space-y-8" x-data="{ 
    deleteModalOpen: false, 
    deleteUrl: '',
    openDeleteModal(url) {
        this.deleteUrl = url;
        $dispatch('open-modal', 'delete-confirmation');
    }
}">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Laporan Sekolah</h1>
            <p class="text-slate-500 mt-2 text-lg">Kelola dan pantau status laporan administratif sekolah.</p>
        </div>
        <div class="flex items-center gap-3">
             <a href="{{ route('school-reports.create') }}" 
               class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="p-4 rounded-xl bg-emerald-50 border border-emerald-100 flex items-start gap-3">
        <div class="shrink-0 text-emerald-500">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
        <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        {{-- Toolbar (Optional Search/Filter could go here) --}}
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-4">Judul Laporan</th>
                        <th class="px-6 py-4">Tipe & Periode</th>
                        <th class="px-6 py-4">Waktu Upload</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($reports as $report)
                    <tr class="group hover:bg-slate-50/80 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-100 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <a href="{{ route('school-reports.show', $report->id) }}" class="font-semibold text-slate-800 hover:text-indigo-600 transition-colors line-clamp-1">
                                        {{ $report->title }}
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-slate-700">{{ ucfirst($report->report_type) }}</span>
                                <span class="text-xs text-slate-400">
                                    {{ $report->report_period ? $report->report_period->format('M Y') : '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-500">
                            {{ $report->created_at->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusStyles = [
                                    'submitted' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'reviewed' => 'bg-purple-50 text-purple-700 border-purple-100',
                                    'accepted' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-100',
                                    'revision_needed' => 'bg-amber-50 text-amber-700 border-amber-100',
                                ];
                                $style = $statusStyles[$report->status] ?? 'bg-slate-50 text-slate-700 border-slate-100';
                                
                                $statusLabel = [
                                    'submitted' => 'Terkirim',
                                    'reviewed' => 'Ditinjau',
                                    'accepted' => 'Diterima',
                                    'rejected' => 'Ditolak',
                                    'revision_needed' => 'Perlu Revisi',
                                ][$report->status] ?? $report->status;
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $style }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('school-reports.show', $report->id) }}" 
                                   class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all" 
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                <a href="{{ Storage::url($report->file_path) }}" target="_blank" 
                                   class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" 
                                   title="Download File">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </a>

                                <button type="button" 
                                        @click="openDeleteModal('{{ route('school-reports.destroy', $report->id) }}')"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" 
                                        title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-slate-900 font-medium text-lg">Belum ada laporan</h3>
                                <p class="text-slate-500 mt-1 max-w-sm">Mulai dengan membuat laporan baru untuk dikirimkan ke Dinas Pendidikan.</p>
                                <a href="{{ route('school-reports.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-700 font-medium">Buat Laporan &rarr;</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reports->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $reports->links() }}
        </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="delete-confirmation" focusable>
        <form method="POST" :action="deleteUrl" class="p-6">
            @csrf
            @method('DELETE')
            
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Hapus Laporan?</h2>
                    <p class="text-slate-500 text-sm">Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close-modal', 'delete-confirmation')" 
                    class="px-4 py-2 bg-white text-slate-700 border border-slate-300 rounded-lg hover:bg-slate-50 font-medium transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition shadow-lg shadow-red-200">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </x-modal>
</div>
@endsection
