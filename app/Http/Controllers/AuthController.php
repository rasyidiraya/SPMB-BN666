<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pendaftar\Pengguna;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login user
    public function login(Request $request)
    {
        // Validasi input email & password
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cari user berdasarkan email
        $user = Pengguna::where('email', $credentials['email'])->first();

        if ($user && password_verify($credentials['password'], $user->password_hash)) {
            // Cek apakah user aktif
            if (!$user->aktif) {
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
            }

            // Tentukan guard sesuai role user
            $guard = match ($user->role) {
                'pendaftar' => 'pengguna',
                'admin' => 'admin',
                'verifikator_adm' => 'verifikator',
                'keuangan' => 'keuangan',
                'kepsek' => 'kepsek',
                default => 'pengguna'
            };

            // Login user
            Auth::guard($guard)->login($user);

            // Catat aktivitas login ke log
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'login',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role' => $user->role
                ],
                'waktu' => now(),
                'ip' => $request->ip()
            ]);

            // Redirect ke dashboard sesuai role
            return match ($user->role) {
                'pendaftar' => redirect()->route('pendaftar.dashboard'),
                'admin' => redirect()->route('admin.dashboard'),
                'verifikator_adm' => redirect()->route('verifikator.index'),
                'keuangan' => redirect()->route('keuangan.dashboard'),
                'kepsek' => redirect()->route('kepsek.dashboard'),
                default => redirect()->route('pendaftar.dashboard')
            };
        }

        return back()->withErrors(['email' => 'Login gagal']);
    }

    // Tampilkan halaman registrasi
    public function showRegistrasi()
    {
        return view('auth.register');
    }

    // Proses registrasi user baru (tanpa OTP)
    public function registrasi(Request $request)
    {
        // Validasi input form registrasi
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:pengguna,email',
            'hp' => 'required|string|max:20',
            'password' => 'required|min:6|confirmed'
        ]);

        // Simpan user baru ke database
        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password_hash' => Hash::make($request->password),
            'role' => 'pendaftar',
            'aktif' => true
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Proses logout user
    public function logout(Request $request)
    {
        // Cek semua guard dan logout dari yang aktif
        $guards = ['pengguna', 'admin', 'verifikator', 'keuangan', 'kepsek'];
        $user = null;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                Auth::guard($guard)->logout();
                break;
            }
        }

        if ($user) {
            // Catat aktivitas logout ke log
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'logout',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role' => $user->role
                ],
                'waktu' => now(),
                'ip' => $request->ip()
            ]);
        }

        // Hapus session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('logout', true);
    }
}