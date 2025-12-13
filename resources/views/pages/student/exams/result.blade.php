@extends('layouts.dashboard')

@section('title', 'Hasil Ujian - ' . $exam->title)

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Hasil Ujian</h1>
        <p class="text-gray-500">{{ $exam->title }}</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="p-8 text-center bg-linear-to-b from-blue-50 to-white">
            <p class="text-gray-500 font-medium mb-4 uppercase tracking-wider text-sm">Nilai Total Anda</p>
            <div class="text-8xl font-black text-blue-600 mb-4 tracking-tighter">{{ number_format($attempt->total_score, 2) }}</div>
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $attempt->total_score >= $exam->course->subject->passing_grade ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }} font-bold text-sm">
                @if($attempt->total_score >= $exam->course->subject->passing_grade)
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Lulus (KKM: {{ $exam->course->subject->passing_grade }})
                @else
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    Belum Lulus (KKM: {{ $exam->course->subject->passing_grade }})
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 divide-x divide-gray-100 border-t border-gray-100">
            <div class="p-6 text-center">
                <p class="text-gray-400 text-xs font-bold uppercase mb-1">Waktu Mulai</p>
                <p class="text-gray-800 font-medium">{{ $attempt->started_at->format('H:i') }} WIB</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-gray-400 text-xs font-bold uppercase mb-1">Waktu Selesai</p>
                <p class="text-gray-800 font-medium">{{ $attempt->finished_at->format('H:i') }} WIB</p>
            </div>
        </div>

        <div class="p-6 border-t border-gray-100 text-center">
            <a href="{{ route('student.exams.index') }}" class="text-blue-600 hover:text-blue-800 font-bold text-sm">Kembali ke Daftar Ujian</a>
        </div>
    <div class="mt-8">
        @php
            $now = \Carbon\Carbon::now('Asia/Jakarta');
            $endTime = \Carbon\Carbon::parse($exam->end_time)->shiftTimezone('Asia/Jakarta');
            $showDetails = $now->gt($endTime);
            $mappedAnswers = $attempt->answers->keyBy('exam_question_id');
        @endphp

        @if($showDetails)
            <div class="mb-6 flex items-center gap-2 text-indigo-700 bg-indigo-50 p-4 rounded-lg border border-indigo-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">Pembahasan soal ditampilkan karena ujian telah berakhir.</span>
            </div>

            <div class="space-y-6">
                @foreach($exam->questions as $index => $question)
                    @php
                        $answerRecord = $mappedAnswers[$question->id] ?? null;
                        $userAnswer = $answerRecord->answer ?? null;
                        $receivedScore = $answerRecord->score ?? 0;
                        // Correct if score > 0. For essays, this means teacher gave points. For auto, it matches points.
                        $isCorrect = $receivedScore > 0;
                        $options = $question->options; 
                    @endphp

                    <div class="bg-white p-6 rounded-xl border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }} shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <span class="font-bold text-gray-500 text-sm">Soal {{ $index + 1 }}</span>
                            @if($isCorrect)
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-bold">Benar (+{{ $receivedScore }})</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold">Salah (0)</span>
                            @endif
                        </div>
                        
                        <div class="prose max-w-none text-gray-800 mb-6">
                            {!! nl2br(e($question->question_text)) !!}
                        </div>

                        @if($question->question_type === 'multiple_choice')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($options as $key => $optionLabel)
                                    @php
                                        $stateClass = 'border-gray-200 bg-gray-50';
                                        if($key == $question->correct_answer) {
                                            $stateClass = 'border-green-500 bg-green-50 ring-1 ring-green-500';
                                        } 
                                        // Highlight selected answer if it's wrong
                                        if($key == $userAnswer && !$isCorrect) {
                                            $stateClass = 'border-red-500 bg-red-50 ring-1 ring-red-500';
                                        }
                                        // Highlight selected answer if it's correct (already handled by green above, but let's reinforce)
                                        if($key == $userAnswer && $isCorrect) {
                                             $stateClass = 'border-green-500 bg-green-50 ring-1 ring-green-500';
                                        }
                                    @endphp
                                    <div class="p-3 border rounded-lg flex items-center gap-3 {{ $stateClass }}">
                                        <span class="w-6 h-6 flex items-center justify-center rounded-full border text-xs font-bold {{ $key == $question->correct_answer ? 'bg-green-600 border-green-600 text-white' : ($key == $userAnswer && !$isCorrect ? 'bg-red-600 border-red-600 text-white' : 'bg-white border-gray-300 text-gray-500') }}">
                                            {{ $key }}
                                        </span>
                                        <span class="text-sm text-gray-700">{{ $optionLabel }}</span>
                                        
                                        @if($key == $question->correct_answer)
                                            <svg class="w-5 h-5 ml-auto text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @elseif($key == $userAnswer && !$isCorrect)
                                            <svg class="w-5 h-5 ml-auto text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->question_type === 'true_false')
                            <div class="flex gap-4">
                                @foreach(['true' => 'Benar', 'false' => 'Salah'] as $val => $label)
                                    @php
                                         $stateClass = 'border-gray-200 bg-gray-50 opacity-60';
                                         if($val == $question->correct_answer) $stateClass = 'border-green-500 bg-green-50 opacity-100 ring-1 ring-green-500';
                                         if($val == $userAnswer && !$isCorrect) $stateClass = 'border-red-500 bg-red-50 opacity-100 ring-1 ring-red-500';
                                    @endphp
                                    <div class="px-4 py-2 border rounded-lg font-medium text-sm {{ $stateClass }}">
                                        {{ $label }}
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question->question_type === 'essay')
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <p class="text-xs font-bold text-gray-500 uppercase mb-2">Jawaban Anda:</p>
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $userAnswer ?? '(Tidak menjawab)' }}</p>
                            </div>
                        @endif
                        
                        @if(!$isCorrect && $question->question_type !== 'essay')
                             <div class="mt-4 p-3 bg-red-50 rounded-lg text-sm text-red-800 border border-red-100">
                                <span class="font-bold">Jawaban Benar:</span> 
                                @if($question->question_type == 'multiple_choice')
                                    {{ $question->correct_answer }}. {{ $options[$question->correct_answer] ?? '' }}
                                @elseif($question->question_type == 'true_false')
                                    {{ $question->correct_answer == 'true' ? 'Benar' : 'Salah' }}
                                @endif
                             </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center mt-6">
                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-800 mb-1">Detail Jawaban Disembunyikan</h3>
                <p class="text-sm text-gray-600">
                    Pembahasan soal dan kunci jawaban baru dapat dilihat setelah waktu ujian berakhir secara serentak pada: <br>
                    <strong>{{ $endTime->format('d M Y, H:i') }} WIB</strong>
                </p>
            </div>
        @endif
    </div>
@endsection
