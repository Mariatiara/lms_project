@extends('layouts.dashboard')

@section('title', $exam->title)

@section('content')
<div class="p-6 max-w-5xl mx-auto" x-data="examTimer()">
    <form action="{{ route('student.exams.submit', $exam->id) }}" method="POST" id="examForm" @submit="submitForm">
        @csrf
        
        <div class="flex flex-col lg:flex-row gap-6 items-start">
            <!-- Left: Questions -->
            <div class="flex-1 w-full space-y-8">
                @foreach($exam->questions as $index => $question)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative" id="q-{{ $question->id }}">
                        <div class="flex items-start gap-4 mb-4">
                            <span class="bg-blue-100 text-blue-700 w-8 h-8 rounded-lg flex items-center justify-center font-bold shrink-0">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1 prose max-w-none text-gray-800">
                                {!! nl2br(e($question->question_text)) !!}
                            </div>
                        </div>

                        <div class="ml-12 space-y-3">
                            @if($question->question_type === 'multiple_choice')
                                @foreach($question->options as $key => $option)
                                    <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition-all group">
                                        <div class="flex items-center h-5">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" class="text-blue-600 focus:ring-blue-500 border-gray-300">
                                        </div>
                                        <div class="flex-1 text-sm text-gray-700 font-medium group-hover:text-gray-900">
                                            <span class="font-bold mr-2">{{ $key }}.</span> {{ $option }}
                                        </div>
                                    </label>
                                @endforeach
                            @elseif($question->question_type === 'true_false')
                                 <div class="flex gap-4">
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="True" class="text-blue-600 focus:ring-blue-500">
                                        <span class="font-bold text-gray-700">True (Benar)</span>
                                    </label>
                                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-200 cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="False" class="text-blue-600 focus:ring-blue-500">
                                        <span class="font-bold text-gray-700">False (Salah)</span>
                                    </label>
                                </div>
                            @elseif($question->question_type === 'essay')
                                <textarea name="answers[{{ $question->id }}]" rows="5" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-4 text-sm" placeholder="Tulis jawaban anda di sini..."></textarea>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Right: Timer & Navigation -->
            <div class="w-full lg:w-80 shrink-0 sticky top-6 space-y-6">
                <!-- Timer Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">Sisa Waktu</p>
                    <div class="text-4xl font-black text-gray-900 tabular-nums tracking-tight" x-text="timeDisplay">
                        --:--:--
                    </div>
                </div>

                <!-- Navigation Grid -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm">Navigasi Soal</h3>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach($exam->questions as $index => $question)
                            <a href="#q-{{ $question->id }}" class="w-10 h-10 rounded-lg flex items-center justify-center text-sm font-medium bg-gray-50 text-gray-600 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 transition-colors">
                                {{ $index + 1 }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" onclick="return confirm('Apakah anda yakin ingin mengumpulkan ujian ini? Aksi ini tidak dapat dibatalkan.')" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-green-500/30 transition-all flex items-center justify-center gap-2">
                    Kumpulkan Jawaban
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function examTimer() {
        return {
            endTime: new Date('{{ $attempt->started_at->addMinutes($exam->duration_minutes)->toIso8601String() }}').getTime(),
            timeDisplay: '--:--:--',
            interval: null,

            init() {
                this.updateTimer();
                this.interval = setInterval(() => {
                    this.updateTimer();
                }, 1000);
            },

            updateTimer() {
                const now = new Date().getTime();
                const distance = this.endTime - now;

                if (distance < 0) {
                    clearInterval(this.interval);
                    this.timeDisplay = '00:00:00';
                    alert('Waktu habis! Jawaban anda akan otomatis dikumpulkan.');
                    document.getElementById('examForm').submit();
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                this.timeDisplay = 
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }
        }
    }
</script>
@endsection
