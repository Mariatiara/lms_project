@extends('layouts.app')

@section('title', 'Login Guru')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-green-100 to-green-200">
  <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Login Guru</h2>

    <form action="{{ route('login.submit') }}" method="POST">
      @csrf

      <input type="hidden" name="role" value="guru">

      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1">Email</label>
        <input name="email" type="email" required
               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
      </div>

      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-1">Password</label>
        <input name="password" type="password" required minlength="6"
               class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white font-bold py-2 rounded-lg hover:bg-blue-700 transition">
        Masuk
      </button>
    </form>

    <p class="text-center text-gray-600 text-sm mt-4">© 2025 LearnFlux LMS</p>
  </div>
</div>
@endsection
