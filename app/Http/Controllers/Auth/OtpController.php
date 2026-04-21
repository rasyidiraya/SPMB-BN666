<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar\Pengguna;
use App\Models\LogAktivitas;

class OtpController extends Controller
{
    // Fungsi kirim OTP ke email user saat registrasi
    public function sendOtp(Request $request)
    {
        // Validasi input form
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email',
            'hp' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat kode OTP 6 digit
        $otp = rand(100000, 999999);
        
        // Simpan data registrasi + OTP ke session (belum ke database)
        Session::put('registration_data', [
            'nama' => $request->nama,
            'email' => $request->email,
            'hp' => $request->hp,
            'password' => Hash::make($request->password),
            'otp' => $otp,
            'otp_expires' => now()->addMinutes(5) // Berlaku 5 menit
        ]);

        try {
            // Kirim email OTP ke user
            Mail::send('emails.otp', ['otp' => $otp, 'nama' => $request->nama], function($message) use ($request) {
                $message->to($request->email)->subject('Kode OTP Registrasi SPMB');
            });

            $message = 'Kode OTP telah dikirim ke email Anda';
            
            // Cek apakah email beneran terkirim atau masih mode testing
            if (config('mail.default') === 'log') {
                $message = 'Testing Mode - OTP: ' . $otp . ' (Email tidak dikirim, gunakan kode ini)';
            } elseif (config('mail.mailers.smtp.username') === 'ganti-dengan-app-password' || 
                     config('mail.mailers.smtp.password') === 'ganti-dengan-app-password') {
                $message = 'Email belum dikonfigurasi. Setup Gmail di file .env dulu. Testing OTP: ' . $otp;
            }

            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error('OTP Email Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email OTP: ' . $e->getMessage()]);
        }
    }

    // Fungsi verifikasi kode OTP yang dimasukkan user
    public function verifyOtp(Request $request)
    {
        // Ambil data registrasi dari session
        $registrationData = Session::get('registration_data');
        
        // Cek OTP cocok dan belum expired
        if (!$registrationData || $request->otp != $registrationData['otp'] || now()->gt($registrationData['otp_expires'])) {
            return response()->json(['success' => false, 'message' => 'Kode OTP salah atau kedaluwarsa']);
        }

        try {
            // OTP benar, buat akun baru di database
            $user = Pengguna::create([
                'nama' => $registrationData['nama'],
                'email' => $registrationData['email'],
                'hp' => $registrationData['hp'],
                'password_hash' => $registrationData['password'],
                'role' => 'pendaftar',
                'aktif' => true
            ]);

            // Auto-login setelah registrasi berhasil
            Auth::guard('pengguna')->login($user);
            
            // Catat aktivitas registrasi ke log
            LogAktivitas::create([
                'user_id' => $user->id,
                'aksi' => 'register_and_login',
                'objek' => 'auth',
                'objek_data' => [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'role' => $user->role
                ],
                'waktu' => now(),
                'ip' => $request->ip()
            ]);

            // Hapus data registrasi dari session
            Session::forget('registration_data');

            return response()->json([
                'success' => true, 
                'message' => 'Registrasi berhasil! Anda akan diarahkan ke dashboard.',
                'redirect' => route('pendaftar.dashboard')
            ]);
        } catch (\Exception $e) {
            Log::error('Account creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat akun: ' . $e->getMessage()]);
        }
    }

    // Fungsi kirim ulang OTP kalau user tidak menerima email
    public function resendOtp(Request $request)
    {
        // Ambil data registrasi dari session
        $registrationData = Session::get('registration_data');
        
        if (!$registrationData) {
            return response()->json(['success' => false, 'message' => 'Data registrasi tidak ditemukan']);
        }

        // Buat kode OTP baru, timpa yang lama
        $otp = rand(100000, 999999);
        $registrationData['otp'] = $otp;
        $registrationData['otp_expires'] = now()->addMinutes(5);
        Session::put('registration_data', $registrationData);

        try {
            // Kirim ulang email OTP
            Mail::send('emails.otp', ['otp' => $otp, 'nama' => $registrationData['nama']], function($message) use ($registrationData) {
                $message->to($registrationData['email'])->subject('Kode OTP Registrasi SPMB (Kirim Ulang)');
            });

            $message = 'Kode OTP baru telah dikirim';
            if (config('mail.default') === 'log') {
                $message = 'Testing Mode - OTP Baru: ' . $otp;
            }
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim ulang OTP']);
        }
    }
}