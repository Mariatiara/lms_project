@extends('layouts.dashboard')

@section('content')

<h2 class="text-2xl font-bold mb-4">Buat Tugas Baru</h2>

<form action="{{ route('assignments.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label>Judul Tugas</label>
        <input name="judul" class="w-full border p-2 rounded" required>
    </div>

    <div>
        <label>Mata Pelajaran & Kelas</label>
        <select name="course_id" class="w-full border p-2 rounded" required>
            <option value="">-- Pilih --</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}">
                    {{ $course->subject->name }} - {{ $course->classroom->name }} ({{ $course->classroom->school->name ?? '' }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Deskripsi</label>
        <textarea name="deskripsi" class="w-full border p-2 rounded"></textarea>
    </div>

    <div>
        <label>Deadline</label>
        {{-- Use datetime-local for accurate deadline --}}
        <input type="datetime-local" name="deadline" class="w-full border p-2 rounded" required>
    </div>

    <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
</form>

@endsection
