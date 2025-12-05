<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\PengumpulanController;
use App\Http\Controllers\RaporController;
use App\Http\Controllers\RaporPdfController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ScheduleController;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

// =====================================================================
// PUBLIC
// =====================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');

// AUTH
Route::get('/login/{role}', [AuthController::class, 'showLogin'])->name('login.role');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// =====================================================================
// DASHBOARD BY ROLE
// =====================================================================
Route::middleware(['auth', 'role:kepala_sekolah'])->get('/dashboard/kepala-sekolah', fn() => view('dashboard.kepala-sekolah'))->name('dashboard.kepsek');
Route::middleware(['auth', 'role:guru'])->get('/dashboard/guru', fn() => view('dashboard.guru'))->name('dashboard.guru');
Route::middleware(['auth', 'role:siswa'])->get('/dashboard/siswa', fn() => view('dashboard.siswa'))->name('dashboard.siswa');
Route::middleware(['auth', 'role:orang_tua'])->get('/dashboard/orang-tua', fn() => view('dashboard.orang-tua'))->name('dashboard.orangtua');
Route::middleware(['auth', 'role:dinas'])->get('/dashboard/dinas', fn() => view('dashboard.dinas'))->name('dashboard.dinas');

// =====================================================================
// CRUD SISWA
// =====================================================================
Route::middleware(['auth', 'role:kepsek,guru'])->group(function () {
    Route::get('/data-siswa', [StudentController::class, 'index'])->name('siswa.index');
    Route::post('/data-siswa', [StudentController::class, 'store'])->name('siswa.store');
    Route::post('/data-siswa/{id}', [StudentController::class, 'update'])->name('siswa.update');
    Route::delete('/data-siswa/{id}', [StudentController::class, 'destroy'])->name('siswa.delete');
});

// =====================================================================
// CRUD GURU
// =====================================================================
Route::middleware(['auth', 'role:kepsek'])->group(function () {
    Route::get('/data-guru', [TeacherController::class, 'index'])->name('guru.index');
    Route::post('/data-guru', [TeacherController::class, 'store'])->name('guru.store');
    Route::post('/data-guru/{id}', [TeacherController::class, 'update'])->name('guru.update');
    Route::delete('/data-guru/{id}', [TeacherController::class, 'destroy'])->name('guru.delete');
});

// =====================================================================
// JADWAL
// =====================================================================
Route::middleware(['auth', 'role:guru,kepsek'])->group(function () {
    Route::get('/jadwal', [ScheduleController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [ScheduleController::class, 'store'])->name('jadwal.store');
    Route::post('/jadwal/{id}', [ScheduleController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [ScheduleController::class, 'destroy'])->name('jadwal.delete');
});

// =====================================================================
// NILAI + RAPOR
// =====================================================================
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
});

Route::middleware(['auth', 'role:siswa'])->get('/nilai-saya', [NilaiController::class, 'siswaNilai'])->name('nilai.siswa');

// Rapor Semester
Route::middleware('auth')->group(function () {
    Route::get('/rapor', [RaporController::class, 'index'])->name('rapor.index');
    Route::post('/rapor/store', [RaporController::class, 'store'])->name('rapor.store');
    Route::get('/rapor/siswa/{id}', [RaporController::class, 'show'])->name('rapor.siswa');
});
Route::get('/rapor/pdf/{id}', [RaporPdfController::class, 'generate'])
     ->middleware('auth')
     ->name('rapor.pdf');

// =====================================================================
// FILE: Tugas & Pengumpulan
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
    Route::post('/tugas/store', [TugasController::class, 'store'])->name('tugas.store');
    Route::post('/tugas/{id}/upload', [PengumpulanController::class, 'upload'])->name('tugas.upload');
    Route::post('/pengumpulan/{id}/nilai', [PengumpulanController::class, 'nilai'])->name('pengumpulan.nilai');
});

// =====================================================================
// ABSENSI
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
});

// =====================================================================
// PENGUMUMAN
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
});

// =====================================================================
// FORUM
// =====================================================================
Route::middleware('auth')->group(function () {
    Route::get('/forum', [ForumController::class, 'index'])->name('forum.index');
    Route::post('/forum/store', [ForumController::class, 'store'])->name('forum.store');
});

// =====================================================================
// PARENT MODE & SUPERADMIN
// =====================================================================
Route::middleware('auth')->get('/parent/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
Route::middleware('auth')->get('/superadmin/dashboard', [SuperadminController::class, 'index'])->name('superadmin.dashboard');

// =====================================================================
// NILAI GURU – Rekap Nilai
// =====================================================================
Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/guru/nilai', [ReportController::class, 'guruIndex'])->name('guru.nilai');
});

// =====================================================================
// CHAT SYSTEM (FINAL — TANPA BENTROK, TANPA MENGGANGGU ROUTE LAIN)
// =====================================================================
Route::middleware('auth')->group(function () {

    // Halaman daftar kontak chat
    Route::get('/chat', [ChatController::class, 'contacts'])->name('chat.index');

    // Buka chat dengan user tertentu
    Route::get('/chat/{id}', [ChatController::class, 'chat'])->name('chat.room');

    // Kirim pesan
    Route::post('/chat/{id}', [ChatController::class, 'send'])->name('chat.send');

    // API untuk badge jumlah pesan belum dibaca
    Route::get('/chat-count', function () {
        $count = \App\Models\Message::where('receiver_id', Auth::id())
                    ->where('is_read', false)
                    ->count();

        return response()->json(['count' => $count]);
    })->name('chat.count');

});

Route::get('/login', function () {
    return redirect()->route('login.role', 'siswa');
})->name('login');

// =====================================================================
// NOTIFIKASI (Fix Error notif.index)
// =====================================================================
Route::middleware('auth')->get('/notifikasi', function () {
    return view('notifikasi.index'); // nanti kamu buat view ini
})->name('notif.index');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::post('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');
});

// =====================================================================
// NOTIFIKASI (COUNT)
// =====================================================================
Route::get('/notif/count', function () {
    return response()->json([
        'count' => \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)
                    ->count()
    ]);
})->name('notif.count');
