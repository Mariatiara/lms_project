@extends('layouts.app')

@section('title', 'Login - ' . ucfirst($role))

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">
            Login Sebagai {{ ucfirst(str_replace('-', ' ', $role)) }}
        </h2>

        <form method="POST" action="{{ route('login.submit') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="role" value="dinas">

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Email</label>
                <input type="email" name="email" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Password</label>
                <input type="password" name="password" minlength="6" required
                       class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Beranda</a>
        </div>
    </div>
</div>
@endsection
