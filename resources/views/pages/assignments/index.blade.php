@extends('layouts.dashboard')

@section('content')

<h2 class="text-2xl font-bold mb-4">Daftar Tugas</h2>

@if(auth()->user()->role == \App\Enums\UserRole::GURU)
<a href="{{ route('assignments.create') }}" 
   class="px-4 py-2 bg-blue-600 text-white rounded inline-block mb-4">
   + Buat Tugas
</a>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
@foreach($tugas as $t)
    <div class="p-4 bg-white shadow rounded">
        <h3 class="font-bold text-lg">{{ $t->title }}</h3>
        <p class="text-sm text-gray-600">Mapel: {{ $t->course->subject->name ?? '-' }}</p>
        <p class="text-sm text-gray-600">Kelas: {{ $t->course->classroom->name ?? '-' }}</p>
        <p class="text-sm text-gray-600">Deadline: {{ $t->due_date ? $t->due_date->format('d M Y H:i') : '-' }}</p>

        <a href="{{ route('assignments.show', $t->id) }}" 
           class="mt-3 inline-block text-blue-600">
            Lihat Detail →
        </a>
    </div>
@endforeach
</div>

@endsection
