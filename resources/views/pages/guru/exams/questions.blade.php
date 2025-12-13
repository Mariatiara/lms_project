@extends('layouts.dashboard')

@section('title', 'Kelola Soal - ' . $exam->title)

@section('content')
<div class="p-6" x-data>
    <div class="mb-6">
        <a href="{{ route('teacher.exams.index', $exam->course_id) }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
            </svg>
            Kembali ke Daftar Ujian
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $exam->title }} - Soal</h1>
                <p class="text-gray-500 mt-1">Total Soal: {{ $exam->questions->count() }} | Total Poin: {{ $exam->questions->sum('points') }}</p>
            </div>
            <button @click="$dispatch('open-modal', 'add-question')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Tambah Soal
            </button>
        </div>
    </div>

    <!-- Questions List -->
    <div class="space-y-4">
        @forelse($exam->questions as $index => $question)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-2">
                        <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm font-bold">No. {{ $index + 1 }}</span>
                        <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold uppercase">{{ str_replace('_', ' ', $question->question_type) }}</span>
                        <span class="bg-yellow-50 text-yellow-700 px-2 py-1 rounded text-xs font-bold">{{ $question->points }} Poin</span>
                    </div>
                     <form action="{{ route('teacher.exams.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-400 hover:text-red-600 p-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </form>
                </div>
                
                <div class="prose max-w-none text-gray-800 mb-4">
                    {!! nl2br(e($question->question_text)) !!}
                </div>

                @if($question->question_type === 'multiple_choice' || $question->question_type === 'multiple_answer')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-4 border-l-2 border-gray-100">
                        @foreach($question->options as $key => $option)
                            <div class="flex items-center gap-2 {{ $key == $question->correct_answer ? 'text-green-600 font-bold' : 'text-gray-600' }}">
                                <span class="w-6 h-6 rounded-full border flex items-center justify-center text-xs shrink-0 {{ $key == $question->correct_answer ? 'border-green-600 bg-green-50' : 'border-gray-300' }}">
                                    {{ $key }}
                                </span>
                                <span>{{ $option }}</span>
                                @if($key == $question->correct_answer)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($question->question_type === 'true_false')
                    <div class="flex gap-4 pl-4 border-l-2 border-gray-100">
                        <span class="{{ $question->correct_answer == 'True' ? 'text-green-600 font-bold' : 'text-gray-400' }}">True</span>
                        <span class="{{ $question->correct_answer == 'False' ? 'text-green-600 font-bold' : 'text-gray-400' }}">False</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12 bg-gray-50 rounded-xl border border-gray-100">
                <p class="text-gray-500 italic">Belum ada soal ditambahkan.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Add Question -->
<x-modal name="add-question" :show="$errors->any()" maxWidth="2xl">
    <div class="p-6" x-data="{ 
        type: 'multiple_choice', 
        options: [{'key': 'A', 'value': ''}, {'key': 'B', 'value': ''}, {'key': 'C', 'value': ''}, {'key': 'D', 'value': ''}] 
    }">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Tambah Soal Baru</h3>
            <button @click="$dispatch('close-modal', 'add-question')" class="text-gray-400 hover:text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="{{ route('teacher.exams.questions.store', $exam->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Soal</label>
                <select name="question_type" x-model="type" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
                    <option value="multiple_choice">Pilihan Ganda</option>
                    <option value="essay">Esai / Uraian</option>
                    <option value="true_false">Benar / Salah</option>
                    {{-- <option value="short_answer">Isian Singkat</option> --}}
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                <textarea name="question_text" rows="3" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border" placeholder="Tuliskan pertanyaan..."></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Poin</label>
                <input type="number" name="points" value="5" min="1" required class="w-24 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-4 py-2 border">
            </div>

            <!-- Multiple Choice Options -->
            <div x-show="type === 'multiple_choice'" class="space-y-3 mb-4 bg-gray-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Opsi Jawaban</label>
                <template x-for="(option, index) in options" :key="index">
                    <div class="flex gap-2 items-center">
                        <span class="w-8 h-8 flex items-center justify-center bg-white border border-gray-300 rounded font-bold text-gray-500" x-text="option.key"></span>
                        <input type="text" :name="'options[' + option.key + ']'" required class="flex-1 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm px-3 py-2 border text-sm" placeholder="Isi jawaban...">
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="correct_answer" :value="option.key" required class="text-blue-600 focus:ring-blue-500">
                            <span class="text-xs text-gray-500">Benar</span>
                        </label>
                    </div>
                </template>
            </div>

             <!-- True False -->
             <div x-show="type === 'true_false'" class="space-y-3 mb-4 bg-gray-50 p-4 rounded-lg">
                <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Jawaban</label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded border border-gray-200">
                        <input type="radio" name="correct_answer" value="True" x-bind:required="type === 'true_false'" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-green-600">True (Benar)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded border border-gray-200">
                        <input type="radio" name="correct_answer" value="False" x-bind:required="type === 'true_false'" class="text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-red-600">False (Salah)</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg shadow-blue-500/30 transition-all">
                    Simpan Soal
                </button>
            </div>
        </form>
    </div>
</x-modal>
@endsection
