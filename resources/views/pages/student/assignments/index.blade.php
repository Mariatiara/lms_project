@extends('layouts.dashboard')

@section('title', 'Daftar Tugas')

@section('content')
<div class="p-6" x-data="{ filter: 'all' }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Tugas</h1>
            <p class="text-gray-500">Pantau dan kerjakan tugas-tugasmu tepat waktu.</p>
        </div>
        <div class="mt-4 md:mt-0 bg-white p-1 rounded-lg border border-gray-200 flex space-x-1">
            <button @click="filter = 'all'" :class="{'bg-blue-100 text-blue-700': filter === 'all', 'text-gray-500 hover:bg-gray-50': filter !== 'all'}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Semua
            </button>
            <button @click="filter = 'pending'" :class="{'bg-red-100 text-red-700': filter === 'pending', 'text-gray-500 hover:bg-gray-50': filter !== 'pending'}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Belum Selesai
            </button>
            <button @click="filter = 'completed'" :class="{'bg-green-100 text-green-700': filter === 'completed', 'text-gray-500 hover:bg-gray-50': filter !== 'completed'}" class="px-4 py-2 text-sm font-medium rounded-md transition-colors">
                Selesai
            </button>
        </div>
    </div>

    @if($assignments->isEmpty())
         <div class="bg-white rounded-xl shadow-sm p-10 text-center border border-gray-100">
            <p class="text-gray-500">Belum ada tugas yang diberikan.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($assignments as $assignment)
                @php
                    $submission = $assignment->submissions->first();
                    $isLate = \Carbon\Carbon::now()->gt($assignment->due_date) && !$submission;
                    $status = $submission ? 'completed' : 'pending';
                @endphp
                <div x-show="filter === 'all' || filter === '{{ $status }}'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:border-blue-300 transition-colors flex flex-col md:flex-row justify-between md:items-center">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 {{ $submission ? 'bg-green-100 text-green-600' : 'bg-orange-100 text-orange-600' }} rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-bold text-lg text-gray-800">{{ $assignment->title }}</h3>
                                @if($isLate)
                                    <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded font-bold">Terlambat</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mb-1">{{ $assignment->course->subject->name ?? 'Mapel' }} &bull; Guru: {{ $assignment->course->teacher->user->name ?? '-' }}</p>
                            <div class="text-xs text-gray-400">
                                Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y, H:i') }}
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 md:mt-0 flex items-center space-x-4">
                        @if($submission)
                            <div class="text-right mr-4">
                                <span class="block text-xs text-gray-400">Nilai</span>
                                <span class="font-bold text-lg {{ $submission->score >= 75 ? 'text-green-600' : 'text-gray-800' }}">
                                    {{ $submission->score ?? 'Menunggu' }}
                                </span>
                            </div>
                            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors text-sm">
                                Detail
                            </a>
                        @else
                            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-sm shadow-lg shadow-blue-200">
                                Kerjakan
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
