@extends('layouts.dashboard')

@section('title', 'Perhitungan & Bobot Nilai')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-8">
    {{-- Header --}}
    <div class="relative rounded-3xl overflow-hidden bg-linear-to-r from-teal-500 to-emerald-600 shadow-xl">
        <div class="absolute inset-0 bg-white/10 opacity-50 pattern-grid-lg"></div>
        <div class="relative p-8 md:p-10 text-white flex justify-between items-center">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-2">Pengaturan Bobot Nilai</h1>
                <p class="text-teal-50 max-w-2xl text-lg opacity-90">
                    Tentukan formula penilaian rapor sekolah Anda dengan mengatur rasio bobot untuk setiap komponen penilaian.
                </p>
            </div>
            <div class="hidden md:block opacity-80">
                <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-50 hover:text-emerald-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    @endif

    <form action="{{ route('school.grade-weights.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @csrf
        
        {{-- Left Column: Input Cards --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold">1</span>
                    Atur Komposisi Nilai
                </h3>

                <div class="space-y-6">
                    <!-- Daily Weight -->
                    <div class="group border border-gray-200 rounded-xl p-5 hover:border-blue-300 hover:shadow-md transition-all duration-300 bg-linear-to-br from-white to-blue-50/30">
                        <div class="flex items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <label for="daily" class="block font-bold text-gray-800 text-lg">Nilai Harian (H)</label>
                                    <p class="text-sm text-gray-500">Rata-rata Tugas & Ulangan Harian</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 font-medium text-sm">Bobot:</span>
                                <input type="number" id="daily" name="daily" min="0" value="{{ $weights['daily']->weight ?? 2 }}" 
                                    class="w-20 text-center font-bold text-xl text-blue-600 border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                    oninput="updateSimulation()">
                            </div>
                        </div>
                    </div>

                    <!-- Mid Term Weight -->
                    <div class="group border border-gray-200 rounded-xl p-5 hover:border-indigo-300 hover:shadow-md transition-all duration-300 bg-linear-to-br from-white to-indigo-50/30">
                        <div class="flex items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="bg-indigo-100 p-3 rounded-xl text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <label for="mid_term" class="block font-bold text-gray-800 text-lg">Nilai UTS (T)</label>
                                    <p class="text-sm text-gray-500">Ujian Tengah Semester</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 font-medium text-sm">Bobot:</span>
                                <input type="number" id="mid_term" name="mid_term" min="0" value="{{ $weights['mid_term']->weight ?? 1 }}" 
                                    class="w-20 text-center font-bold text-xl text-indigo-600 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"
                                    oninput="updateSimulation()">
                            </div>
                        </div>
                    </div>

                    <!-- Final Term Weight -->
                    <div class="group border border-gray-200 rounded-xl p-5 hover:border-purple-300 hover:shadow-md transition-all duration-300 bg-linear-to-br from-white to-purple-50/30">
                        <div class="flex items-start md:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="bg-purple-100 p-3 rounded-xl text-purple-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                                <div>
                                    <label for="final_term" class="block font-bold text-gray-800 text-lg">Nilai UAS (A)</label>
                                    <p class="text-sm text-gray-500">Ujian Akhir Semester</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-400 font-medium text-sm">Bobot:</span>
                                <input type="number" id="final_term" name="final_term" min="0" value="{{ $weights['final_term']->weight ?? 1 }}" 
                                    class="w-20 text-center font-bold text-xl text-purple-600 border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 shadow-sm"
                                    oninput="updateSimulation()">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-teal-600 text-white px-8 py-3 rounded-xl font-bold text-lg shadow-lg shadow-teal-500/30 hover:bg-teal-700 hover:shadow-teal-500/50 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

        {{-- Right Column: Simulation --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm font-bold">2</span>
                    Simulasi & Visualisasi
                </h3>

                <!-- Formula Card -->
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 mb-6 font-mono text-sm leading-relaxed text-gray-600">
                    <p class="font-bold text-gray-800 mb-2">Rumus Nilai Akhir:</p>
                    <div class="flex items-center justify-center text-center p-3 bg-white rounded-lg border border-gray-200 shadow-xs">
                        <div class="flex flex-col items-center">
                            <span class="border-b border-gray-400 pb-1 mb-1 block w-full px-2">
                                (<span class="text-blue-600 font-bold">H</span>&times;<span id="disp_daily" class="font-bold">2</span>) + 
                                (<span class="text-indigo-600 font-bold">T</span>&times;<span id="disp_mid" class="font-bold">1</span>) + 
                                (<span class="text-purple-600 font-bold">A</span>&times;<span id="disp_final" class="font-bold">1</span>)
                            </span>
                            <span class="font-bold block w-full" id="disp_total">4</span>
                        </div>
                    </div>
                </div>

                <!-- Visual Bar -->
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-600 mb-2">Komposisi Nilai:</p>
                    <div class="w-full h-8 rounded-full overflow-hidden flex shadow-inner bg-gray-100">
                        <div id="bar_daily" class="bg-blue-500 h-full flex items-center justify-center text-xs font-bold text-white transition-all duration-500" style="width: 50%">50%</div>
                        <div id="bar_mid" class="bg-indigo-500 h-full flex items-center justify-center text-xs font-bold text-white transition-all duration-500" style="width: 25%">25%</div>
                        <div id="bar_final" class="bg-purple-500 h-full flex items-center justify-center text-xs font-bold text-white transition-all duration-500" style="width: 25%">25%</div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-2 px-1">
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Harian</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> UTS</div>
                        <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-purple-500"></span> UAS</div>
                    </div>
                </div>

                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                    <h4 class="font-bold text-blue-900 mb-2 text-sm">Contoh Perhitungan:</h4>
                    <p class="text-xs text-blue-800 mb-3 opacity-80">Jika seorang siswa memiliki nilai:</p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        <li class="flex justify-between">
                            <span>Harian (80)</span>
                            <span class="font-mono" id="calc_daily_res"></span>
                        </li>
                        <li class="flex justify-between">
                            <span>UTS (75)</span>
                            <span class="font-mono" id="calc_mid_res"></span>
                        </li>
                        <li class="flex justify-between">
                            <span>UAS (85)</span>
                            <span class="font-mono" id="calc_final_res"></span>
                        </li>
                        <li class="flex justify-between border-t border-blue-200 pt-2 font-bold text-blue-900 mt-1">
                            <span>Nilai Rapot</span>
                            <span class="font-mono text-lg" id="calc_total_res"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function updateSimulation() {
        // Get Inputs
        const wDaily = parseFloat(document.getElementById('daily').value) || 0;
        const wMid = parseFloat(document.getElementById('mid_term').value) || 0;
        const wFinal = parseFloat(document.getElementById('final_term').value) || 0;
        const totalW = wDaily + wMid + wFinal;

        // Update Formula Text
        document.getElementById('disp_daily').textContent = wDaily;
        document.getElementById('disp_mid').textContent = wMid;
        document.getElementById('disp_final').textContent = wFinal;
        document.getElementById('disp_total').textContent = totalW;

        // Update Progress Bar
        const pDaily = totalW > 0 ? (wDaily / totalW) * 100 : 0;
        const pMid = totalW > 0 ? (wMid / totalW) * 100 : 0;
        const pFinal = totalW > 0 ? (wFinal / totalW) * 100 : 0;

        document.getElementById('bar_daily').style.width = pDaily + '%';
        document.getElementById('bar_daily').textContent = Math.round(pDaily) + '%';
        
        document.getElementById('bar_mid').style.width = pMid + '%';
        document.getElementById('bar_mid').textContent = Math.round(pMid) + '%';

        document.getElementById('bar_final').style.width = pFinal + '%';
        document.getElementById('bar_final').textContent = Math.round(pFinal) + '%';

        // Update Calculation Example (Scores: 80, 75, 85)
        const sDaily = 80;
        const sMid = 75;
        const sFinal = 85;

        // Display sub-calculations
        // (80 x 2)
        const resDaily = sDaily * wDaily;
        const resMid = sMid * wMid;
        const resFinal = sFinal * wFinal;

        document.getElementById('calc_daily_res').textContent = `${sDaily} x ${wDaily}`;
        document.getElementById('calc_mid_res').textContent = `${sMid} x ${wMid}`;
        document.getElementById('calc_final_res').textContent = `${sFinal} x ${wFinal}`;

        const finalScore = totalW > 0 ? (resDaily + resMid + resFinal) / totalW : 0;
        document.getElementById('calc_total_res').textContent = finalScore.toFixed(2);
    }

    // Init
    document.addEventListener('DOMContentLoaded', updateSimulation);
</script>
@endsection
