@extends('layouts.dashboard')

@section('title', 'Koreksi Jawaban - ' . $attempt->student->user->name)

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <form action="{{ route('teacher.exams.grade', ['id' => $exam->id, 'attemptId' => $attempt->id]) }}" method="POST">
        @csrf
        
        <div class="flex justify-between items-start mb-6 sticky top-6 z-10 bg-gray-50/95 backdrop-blur py-4 border-b border-gray-200">
            <div>
                <a href="{{ route('teacher.exams.results', $exam->id) }}" class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Kembali ke Hasil
                </a>
                <h1 class="text-2xl font-bold text-gray-900">{{ $attempt->student->user->name }}</h1>
                <p class="text-gray-500">Total Nilai Saat Ini (Skala 100): <span class="font-bold text-blue-600">{{ number_format($attempt->total_score, 2) }}</span></p>
                <p class="text-xs text-gray-400 mt-1">*Nilai akhir dihitung otomatis: (Total Poin / Max Poin) x 100</p>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg shadow-blue-500/30 transition-all">
                Simpan Penilaian
            </button>
        </div>

        @php
            $mappedAnswers = $attempt->answers->keyBy('exam_question_id');
        @endphp

        <div class="space-y-6 pb-12">
            @foreach($exam->questions as $index => $question)
                @php
                    $answer = $mappedAnswers[$question->id] ?? null;
                    $userAnswerVal = $answer ? $answer->answer : null;
                    $currentScore = $answer ? $answer->score : 0;
                @endphp

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <span class="font-bold text-gray-700">Soal {{ $index + 1 }} ({{ $question->points }} Poin)</span>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-600">Nilai:</label>
                            <input type="number" name="scores[{{ $question->id }}]" value="{{ $currentScore }}" min="0" max="{{ $question->points }}" class="w-20 px-2 py-1 text-right border-gray-300 rounded shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold {{ $question->question_type == 'essay' ? 'text-blue-600 border-blue-300 bg-blue-50' : '' }}">
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4 prose text-gray-800">
                            {!! nl2br(e($question->question_text)) !!}
                        </div>

                        @if($question->question_type == 'essay')
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <p class="text-xs font-bold text-yellow-700 uppercase mb-1">Jawaban Siswa (Esai)</p>
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $userAnswerVal ?? '(Tidak Menjawab)' }}</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 gap-2">
                                <p class="text-xs font-bold text-gray-500 uppercase">Jawaban Pilihan:</p>
                                @foreach($question->options as $key => $val)
                                    <div class="flex items-center gap-2 p-2 rounded border {{ $key == $question->correct_answer ? 'bg-green-50 border-green-200' : ($key == $userAnswerVal ? 'bg-red-50 border-red-200' : 'border-transparent') }}">
                                        <span class="w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold {{ $key == $question->correct_answer ? 'bg-green-600 text-white' : ($key == $userAnswerVal ? 'bg-red-600 text-white' : 'bg-gray-200') }}">
                                            {{ $key }}
                                        </span>
                                        <span class="text-sm {{ $key == $question->correct_answer ? 'text-green-800 font-medium' : '' }}">{{ $val }}</span>
                                        @if($key == $userAnswerVal)
                                            <span class="bg-gray-800 text-white text-xs px-2 py-0.5 rounded ml-auto">Dipilih Siswa</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>
@endsection
