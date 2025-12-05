<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Halaman login berdasarkan role
     */
    public function showLogin($role)
    {
        return view('auth.login', ['role' => $role]);
    }

    /**
     * Halaman Register
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        // Redirect berdasarkan role
        return match ($user->role) {
            'dinas'           => redirect()->route('dashboard.dinas'),
            'kepala_sekolah'  => redirect()->route('dashboard.kepsek'),
            'guru'            => redirect()->route('dashboard.guru'),
            'siswa'           => redirect()->route('dashboard.siswa'),
            'orang_tua'       => redirect()->route('dashboard.orangtua'),
            default           => redirect()->route('home'),
        };
    }

    /**
     * Proses Register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('login.role', ['role' => $request->role])
            ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    /**
     * Logout
     * HARUS menggunakan POST agar tidak error 419
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
