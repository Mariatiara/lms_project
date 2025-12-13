@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Profil Saya</h1>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Cover / Header --}}
        <div class="h-32 bg-linear-to-r from-blue-600 to-indigo-700"></div>
        
        <div class="px-8 pb-8">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <div class="flex items-end">
                    <div class="w-24 h-24 rounded-full bg-white p-1 shadow-lg">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama ?? Auth::user()->name) }}&background=random&size=128" alt="Profile" class="w-full h-full rounded-full object-cover">
                    </div>
                    <div class="ml-4 mb-1">
                        <h2 class="text-2xl font-bold text-gray-800">{{ $student->nama ?? Auth::user()->name }}</h2>
                        <p class="text-gray-500">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div>
                     <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        Edit Profil
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Akademik</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500">NIS / NISN</span>
                            <span class="font-medium">{{ $student->nis ?? '-' }} / {{ $student->nisn ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                             <span class="text-gray-500">Kelas</span>
                            <span class="font-medium">{{ $student->classroom->name ?? 'Belum Masuk Kelas' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                             <span class="text-gray-500">Status</span>
                             @if(($student->status->value ?? 'active') == 'active')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-bold">Aktif</span>
                             @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded text-xs font-bold">{{ $student->status->value ?? '-' }}</span>
                             @endif
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                             <span class="text-gray-500">Sekolah</span>
                            <span class="font-medium">{{ $student->school->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pribadi</h3>
                     <div class="space-y-3">
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                            <span class="text-gray-500">Tempat, Tanggal Lahir</span>
                            <span class="font-medium">-</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                             <span class="text-gray-500">Alamat</span>
                            <span class="font-medium">{{ $student->alamat ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 pb-2">
                             <span class="text-gray-500">No. Telepon</span>
                            <span class="font-medium">{{ $student->telepon ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
