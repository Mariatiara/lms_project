@extends('layouts.dashboard')

@section('content')
<div class="p-6">

    {{-- Judul --}}
    <h1 class="text-2xl font-bold mb-6">Pengaturan Akun</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul class="list-disc pl-6">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM UPDATE PROFILE --}}
    <div class="bg-white shadow p-6 rounded mb-8">
        <h2 class="text-xl font-semibold mb-4">Perbarui Profil</h2>

        <form method="POST" action="{{ route('settings.updateProfile') }}">
            @csrf
            <div class="mb-4">
                <label class="font-medium">Nama</label>
                <input type="text" name="name" value="{{ $user->name }}"
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="font-medium">Email</label>
                <input type="email" name="email" value="{{ $user->email }}"
                       class="w-full border p-2 rounded" required>
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Simpan
            </button>
        </form>
    </div>

    {{-- FORM UPDATE PASSWORD --}}
    <div class="bg-white shadow p-6 rounded">
        <h2 class="text-xl font-semibold mb-4">Ganti Password</h2>

        <form method="POST" action="{{ route('settings.updatePassword') }}">
            @csrf

            <div class="mb-4">
                <label class="font-medium">Password Saat Ini</label>
                <input type="password" name="current_password"
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="font-medium">Password Baru</label>
                <input type="password" name="password"
                       class="w-full border p-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="font-medium">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full border p-2 rounded" required>
            </div>

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Ubah Password
            </button>
        </form>
    </div>

</div>
@endsection
