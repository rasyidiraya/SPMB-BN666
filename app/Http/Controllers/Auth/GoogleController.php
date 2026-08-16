<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pendaftar\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect user ke halaman OAuth Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah user login/consent.
     *
     * Logika:
     * - Sudah terdaftar  → langsung login, masuk dashboard
     * - Belum terdaftar  → buat akun pending, kirim OTP ke Gmail,
     *                      redirect ke halaman verifikasi OTP
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        // 1. Ambil data user dari Google
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $e) {
            Log::error('[Google OAuth] Gagal mengambil data user dari Google', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'ip'    => request()->ip(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        try {
            // 2. Cari user — prioritaskan google_id, fallback ke email
            $user = Pengguna::where('google_id', $googleUser->getId())->first()
                ?? Pengguna::where('email', $googleUser->getEmail())->first();

            // ── A. USER SUDAH TERDAFTAR ──────────────────────────────────
            if ($user) {
                if (!$user->aktif) {
                    Log::warning('[Google OAuth] Login ditolak — akun nonaktif', [
                        'user_id' => $user->id,
                        'email'   => $user->email,
                        'ip'      => request()->ip(),
                    ]);
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.']);
                }

                // Simpan google_id jika belum ada (akun lama yang baru pakai Google)
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }

                $this->loginUser($user);

                Log::info('[Google OAuth] Login berhasil', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'role'    => $user->role,
                    'ip'      => request()->ip(),
                ]);

                return $this->redirectToDashboard($user);
            }

            // ── B. USER BELUM TERDAFTAR — buat akun + kirim OTP ─────────
            // Buat akun baru (aktif = false sampai OTP diverifikasi)
            $user = Pengguna::create([
                'nama'          => $googleUser->getName(),
                'email'         => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'hp'            => '',       // kosong dulu, bisa diisi nanti di profil
                'password_hash' => null,
                'role'          => 'pendaftar',
                'aktif'         => false,    // belum aktif sampai OTP diverifikasi
            ]);

            // Buat & simpan OTP ke session
            $otp = rand(100000, 999999);
            Session::put('google_otp_verify', [
                'user_id'     => $user->id,
                'otp'         => $otp,
                'otp_expires' => now()->addMinutes(5)->toDateTimeString(),
            ]);

            // Kirim OTP ke Gmail user
            Mail::send(
                'emails.otp',
                ['otp' => $otp, 'nama' => $user->nama],
                fn ($m) => $m->to($user->email)->subject('Kode OTP Aktivasi Akun SPMB')
            );

            Log::info('[Google OAuth] Akun baru dibuat, OTP dikirim', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'ip'      => request()->ip(),
            ]);

            return redirect()->route('google.otp.verify')
                ->with('info', 'Kode OTP telah dikirim ke ' . $user->email . '. Masukkan kode untuk mengaktifkan akun.');
        } catch (Throwable $e) {
            Log::error('[Google OAuth] Terjadi kesalahan saat memproses login', [
                'error'        => $e->getMessage(),
                'file'         => $e->getFile(),
                'line'         => $e->getLine(),
                'trace'        => $e->getTraceAsString(),
                'google_email' => $googleUser->getEmail() ?? null,
                'ip'           => request()->ip(),
            ]);
            return redirect()->route('login')
                ->withErrors(['email' => 'Terjadi kesalahan saat memproses login Google. Silakan coba lagi.']);
        }
    }

    /**
     * Tampilkan halaman verifikasi OTP setelah registrasi via Google.
     */
    public function showOtpVerify()
    {
        // Kalau tidak ada session OTP → redirect ke login
        if (!Session::has('google_otp_verify')) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi verifikasi tidak ditemukan. Silakan daftar ulang.']);
        }

        $email = '';
        $userId = Session::get('google_otp_verify.user_id');
        if ($userId) {
            $user  = Pengguna::find($userId);
            $email = $user?->email ?? '';
        }

        return view('auth.google-otp', compact('email'));
    }

    /**
     * Proses verifikasi OTP untuk aktivasi akun Google baru.
     */
    public function verifyOtp(\Illuminate\Http\Request $request): RedirectResponse
    {
        $sessionData = Session::get('google_otp_verify');

        if (!$sessionData) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi verifikasi habis. Silakan daftar ulang dengan Google.']);
        }

        // Validasi OTP
        if ($request->otp != $sessionData['otp']) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        if (now()->gt(\Carbon\Carbon::parse($sessionData['otp_expires']))) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan kirim ulang.']);
        }

        try {
            $user = Pengguna::findOrFail($sessionData['user_id']);

            // Aktifkan akun
            $user->update(['aktif' => true]);

            // Hapus session OTP
            Session::forget('google_otp_verify');

            // Login user
            $this->loginUser($user);

            // Catat log aktivitas
            LogAktivitas::create([
                'user_id'    => $user->id,
                'aksi'       => 'register_and_login',
                'objek'      => 'auth',
                'objek_data' => [
                    'metode'     => 'google',
                    'ip'         => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role'       => $user->role,
                ],
                'waktu' => now(),
                'ip'    => $request->ip(),
            ]);

            Log::info('[Google OTP] Registrasi via Google berhasil diverifikasi', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'ip'      => $request->ip(),
            ]);

            return redirect()->route('pendaftar.dashboard')
                ->with('success', 'Akun berhasil diaktifkan. Selamat datang, ' . $user->nama . '!');
        } catch (Throwable $e) {
            Log::error('[Google OTP] Gagal aktivasi akun', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'ip'    => $request->ip(),
            ]);
            return back()->withErrors(['otp' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    /**
     * Kirim ulang OTP untuk aktivasi akun Google.
     */
    public function resendOtp(): RedirectResponse
    {
        $sessionData = Session::get('google_otp_verify');

        if (!$sessionData) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Sesi tidak ditemukan. Silakan daftar ulang.']);
        }

        try {
            $user = Pengguna::findOrFail($sessionData['user_id']);

            $otp = rand(100000, 999999);
            Session::put('google_otp_verify', [
                'user_id'     => $user->id,
                'otp'         => $otp,
                'otp_expires' => now()->addMinutes(5)->toDateTimeString(),
            ]);

            Mail::send(
                'emails.otp',
                ['otp' => $otp, 'nama' => $user->nama],
                fn ($m) => $m->to($user->email)->subject('Kode OTP Aktivasi Akun SPMB (Kirim Ulang)')
            );

            return back()->with('info', 'Kode OTP baru telah dikirim ke ' . $user->email);
        } catch (Throwable $e) {
            Log::error('[Google OTP] Gagal kirim ulang OTP', ['error' => $e->getMessage()]);
            return back()->withErrors(['otp' => 'Gagal mengirim ulang OTP. Coba lagi.']);
        }
    }

    // ── Helper ───────────────────────────────────────────────────────────────

    private function loginUser(Pengguna $user): void
    {
        $guard = match ($user->role) {
            'pendaftar'       => 'pengguna',
            'admin'           => 'admin',
            'verifikator_adm' => 'verifikator',
            'keuangan'        => 'keuangan',
            'kepsek'          => 'kepsek',
            default           => 'pengguna',
        };
        Auth::guard($guard)->login($user, remember: true);
    }

    private function redirectToDashboard(Pengguna $user): RedirectResponse
    {
        return match ($user->role) {
            'pendaftar'       => redirect()->route('pendaftar.dashboard'),
            'admin'           => redirect()->route('admin.dashboard'),
            'verifikator_adm' => redirect()->route('verifikator.index'),
            'keuangan'        => redirect()->route('keuangan.dashboard'),
            'kepsek'          => redirect()->route('kepsek.dashboard'),
            default           => redirect()->route('pendaftar.dashboard'),
        };
    }
}
