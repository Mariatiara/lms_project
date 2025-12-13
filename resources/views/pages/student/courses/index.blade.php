@extends('layouts.dashboard')

@section('title', 'Kelas Saya')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Kelas Saya</h1>
        <p class="text-gray-500">Daftar mata pelajaran yang kamu ikuti semester ini.</p>
    </div>

    @if($courses->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-gray-100">
             <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty" class="h-40 mx-auto opacity-50">
            <p class="text-gray-500 mt-4">Kamu belum terdaftar di kelas manapun.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($courses as $course)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow overflow-hidden flex flex-col h-full">
                {{-- Card Header --}}
                <div class="h-32 bg-linear-to-r from-blue-500 to-indigo-600 p-6 flex flex-col justify-end">
                    <h3 class="text-white font-bold text-xl">{{ $course->subject->name ?? 'Mata Pelajaran' }}</h3>
                    <p class="text-blue-100 text-sm opacity-90">{{ $course->subject->code ?? '' }}</p>
                </div>
                
                {{-- Card Body --}}
                <div class="p-6 flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                             <span class="font-bold">{{ substr($course->teacher->user->name ?? 'G', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pengajar</p>
                            <p class="font-medium text-gray-800 line-clamp-1">{{ $course->teacher->user->name ?? 'Guru' }}</p>
                        </div>
                    </div>

                    {{-- Additional Info --}}
                    <div class="grid grid-cols-2 gap-4 text-sm mt-4">
                        <div class="bg-gray-50 p-2 rounded-lg text-center">
                            <span class="block text-gray-400 text-xs">Materi</span>
                            <span class="font-bold text-gray-700">{{ $course->materials->count() ?? 0 }}</span>
                        </div>
                         <div class="bg-gray-50 p-2 rounded-lg text-center">
                            <span class="block text-gray-400 text-xs">Tugas</span>
                            <span class="font-bold text-gray-700">{{ $course->assignments->count() ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Footer --}}
                <div class="p-4 border-t border-gray-100 bg-gray-50">
                    <a href="{{ route('student.courses.show', $course->id) }}" class="block w-full text-center py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                        Masuk Kelas
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
