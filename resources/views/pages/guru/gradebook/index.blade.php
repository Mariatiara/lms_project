@extends('layouts.dashboard')

@section('title', 'Gradebook: ' . $course->subject->name)

@section('content')
<div class="p-6 space-y-6">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('teacher.courses.show', $course->id) }}" class="text-blue-600 hover:underline flex items-center gap-1 text-sm font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Kelas
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Buku Nilai (Gradebook)</h1>
            <p class="text-gray-500">{{ $course->classroom->name }} &bull; {{ $course->subject->name }}</p>
        </div>
        
         <form action="{{ route('teacher.gradebook.store', $course->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan nilai ini ke Rapor? Data yang sudah ada akan diperbarui.')">
            @csrf
            {{-- Hidden inputs are generated in the loop --}}
            <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-medium shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Simpan ke Rapor
            </button>
        </form>
    </div>
    
    @if(session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <form id="gradeForm" action="{{ route('teacher.gradebook.store', $course->id) }}" method="POST">
                @csrf
                <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700 font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Siswa</th>
                        <th class="px-6 py-4 text-center bg-blue-50/50">Nilai Harian</th>
                        <th class="px-6 py-4 text-center bg-indigo-50/50">UTS</th>
                        <th class="px-6 py-4 text-center bg-purple-50/50">UAS</th>
                        <th class="px-6 py-4 text-center font-extrabold text-gray-900 bg-gray-100">Nilai Akhir</th>
                        <th class="px-6 py-4 text-center bg-gray-100">Predikat</th>
                        <th class="px-6 py-4 text-center">Status Rapor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($gradebook as $data)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $data['student']->user->name }}
                                <div class="text-xs text-gray-400 font-normal mt-0.5">{{ $data['student']->nis ?? '-' }}</div>
                                
                                {{-- HIDDEN INPUTS FOR SUBMISSION --}}
                                <input type="hidden" name="grades[{{ $data['student']->id }}][formative]" value="{{ $data['formative'] }}">
                                <input type="hidden" name="grades[{{ $data['student']->id }}][mid_term]" value="{{ $data['mid_term'] }}">
                                <input type="hidden" name="grades[{{ $data['student']->id }}][final_term]" value="{{ $data['final_term'] }}">
                                <input type="hidden" name="grades[{{ $data['student']->id }}][final_grade]" value="{{ $data['final_grade'] }}">
                                <input type="hidden" name="grades[{{ $data['student']->id }}][predicate]" value="{{ $data['predicate'] }}">
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-blue-600 bg-blue-50/30">
                                {{ $data['formative'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-indigo-600 bg-indigo-50/30">
                                {{ $data['mid_term'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-purple-600 bg-purple-50/30">
                                {{ $data['final_term'] }}
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-gray-900 text-base bg-gray-50 border-l border-r border-gray-100">
                                {{ $data['final_grade'] }}
                            </td>
                            <td class="px-6 py-4 text-center bg-gray-50">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold 
                                    {{ $data['predicate'] == 'A' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $data['predicate'] == 'B' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $data['predicate'] == 'C' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $data['predicate'] == 'D' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $data['predicate'] == 'E' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ $data['predicate'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($data['is_finalized'])
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        Tersimpan
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        Belum Disimpan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </form>
        </div>
        @if(count($gradebook) == 0)
            <div class="p-12 text-center">
                <p class="text-gray-500 italic">Belum ada siswa di kelas ini.</p>
            </div>
        @endif
    </div>
</div>
@endsection