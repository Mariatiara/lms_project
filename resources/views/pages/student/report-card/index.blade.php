@extends('layouts.dashboard')

@section('title', 'Rapor Semester')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Hasil Belajar (Rapor)</h1>
    </div>

    @forelse($academicYears as $year)
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $year->name }}</h2>
                    <span class="text-sm text-gray-500 uppercase font-medium tracking-wide">Semester {{ $year->semester }}</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-gray-500 border-b border-gray-100">
                            <th class="px-6 py-3 font-semibold">Mata Pelajaran</th>
                            <th class="px-6 py-3 font-semibold text-center">KKM</th>
                            <th class="px-6 py-3 font-semibold text-center">Nilai Akhir</th>
                            <th class="px-6 py-3 font-semibold text-center">Predikat</th>
                            <th class="px-6 py-3 font-semibold">Catatan Guru</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($reportCards[$year->id] as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $report->subject->name }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-500">
                                    75 <!-- Hardcoded KKM for now, can be dynamic later -->
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600">
                                    {{ $report->final_grade }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-bold 
                                        {{ $report->predicate == 'A' ? 'bg-green-100 text-green-700' : 
                                           ($report->predicate == 'B' ? 'bg-blue-100 text-blue-700' : 
                                           ($report->predicate == 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                                        {{ $report->predicate }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 italic">
                                    {{ $report->comments ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 border-dashed">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Belum ada data rapor</h3>
            <p class="text-gray-500">Nilai rapor belum diterbitkan oleh guru.</p>
        </div>
    @endforelse
</div>
@endsection
