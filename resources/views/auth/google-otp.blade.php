@extends('welcome')

@section('content')
  <style>
    .auth-wrapper { min-height: 100vh; padding-bottom: 100px; }
  </style>

  <div class="auth-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card auth-card shadow-lg">
            <div class="auth-header">
              <i class="bi bi-envelope-check-fill"></i>
              <h2 class="fw-bold mb-2 ucapan">Verifikasi OTP</h2>
              <p class="mb-0 opacity-75">Aktivasi akun Google Anda</p>
            </div>

            <div class="card-body p-4 p-md-5">

              {{-- Alert info / error --}}
              @if(session('info'))
                <div class="alert alert-info border-0 shadow-sm">
                  <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
                </div>
              @endif

              @if($errors->has('otp'))
                <div class="alert alert-danger border-0 shadow-sm">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first('otp') }}
                </div>
              @endif

              <div class="text-center mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 48 48" class="mb-3">
                  <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9 3.2l6.7-6.7C35.8 2.5 30.3 0 24 0 14.6 0 6.6 5.4 2.6 13.3l7.8 6C12.4 13 17.8 9.5 24 9.5z"/>
                  <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8C43.7 37.3 46.5 31.3 46.5 24.5z"/>
                  <path fill="#FBBC05" d="M10.4 28.7A14.4 14.4 0 0 1 9.5 24c0-1.6.3-3.2.8-4.7l-7.8-6A23.9 23.9 0 0 0 0 24c0 3.9.9 7.5 2.6 10.7l7.8-6z"/>
                  <path fill="#34A853" d="M24 48c6.3 0 11.6-2.1 15.4-5.7l-7.5-5.8c-2.1 1.4-4.8 2.3-7.9 2.3-6.2 0-11.5-4.2-13.4-9.9l-7.8 6C6.6 42.6 14.6 48 24 48z"/>
                </svg>
                <p class="text-muted mb-1">Kode OTP telah dikirim ke Gmail:</p>
                <p class="fw-bold" style="color: #0074b7;">{{ $email }}</p>
                <p class="text-muted small">Berlaku selama <strong>5 menit</strong>. Cek folder Spam jika tidak masuk inbox.</p>
              </div>

              <form method="POST" action="{{ route('google.otp.verify.post') }}">
                @csrf

                {{-- OTP Input 6 kotak --}}
                <div class="mb-4">
                  <label class="form-label text-center d-block fw-semibold">Masukkan Kode OTP (6 digit)</label>
                  <div class="d-flex justify-content-center gap-2">
                    @for ($i = 0; $i < 6; $i++)
                      <input type="text" class="form-control text-center otp-box" maxlength="1"
                        style="width: 50px; height: 50px; font-size: 1.5rem; font-weight: bold;">
                    @endfor
                  </div>
                  <input type="hidden" name="otp" id="otpHidden">
                </div>

                <button type="submit" id="verifyBtn" class="btn btn-gradient w-100 mb-3" disabled>
                  <i class="bi bi-check-circle-fill me-2"></i>Verifikasi & Aktifkan Akun
                </button>
              </form>

              {{-- Kirim Ulang OTP --}}
              <div class="text-center">
                <p class="text-muted mb-2">Tidak menerima kode?</p>
                <form method="POST" action="{{ route('google.otp.resend') }}" id="resendForm">
                  @csrf
                  <button type="submit" id="resendBtn" class="btn"
                    style="border: 2px solid #0074b7; color: #0074b7;">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    <span id="resendText">Kirim Ulang OTP</span>
                    <span id="resendCountdown" style="display: none;"></span>
                  </button>
                </form>

                <div class="mt-3">
                  <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mb-2">
                    <i class="bi bi-arrow-left me-2"></i>Kembali ke Login
                  </a>
                  <a href="{{ route('home') }}" class="btn btn-link text-muted w-100">
                    <i class="bi bi-house-fill me-1"></i>Kembali ke Beranda
                  </a>
                </div>
              </div>

            </div>

            <div class="card-footer bg-light text-center py-3 border-0">
              <small class="text-muted">
                <i class="bi bi-shield-check me-1"></i>Sistem Aman & Terpercaya
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const boxes     = document.querySelectorAll('.otp-box');
    const otpHidden = document.getElementById('otpHidden');
    const verifyBtn = document.getElementById('verifyBtn');

    // Navigasi antar kotak OTP
    boxes.forEach((box, i) => {
      box.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, ''); // angka saja
        if (this.value && i < 5) boxes[i + 1].focus();
        syncOtp();
      });

      box.addEventListener('keydown', function (e) {
        if (e.key === 'Backspace' && !this.value && i > 0) boxes[i - 1].focus();
      });

      // Handle paste
      box.addEventListener('paste', function (e) {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        pasted.split('').slice(0, 6).forEach((char, idx) => {
          if (boxes[idx]) boxes[idx].value = char;
        });
        syncOtp();
        const lastFilled = Math.min(pasted.length, 5);
        boxes[lastFilled].focus();
      });
    });

    function syncOtp() {
      const otp = Array.from(boxes).map(b => b.value).join('');
      otpHidden.value = otp;
      verifyBtn.disabled = otp.length !== 6;
    }

    // Countdown kirim ulang (60 detik)
    let timer = 60;
    const resendBtn       = document.getElementById('resendBtn');
    const resendText      = document.getElementById('resendText');
    const resendCountdown = document.getElementById('resendCountdown');

    resendBtn.disabled = true;
    resendText.style.display = 'none';
    resendCountdown.style.display = 'inline';

    const countdown = setInterval(() => {
      resendCountdown.textContent = `Kirim ulang dalam ${timer}s`;
      timer--;
      if (timer < 0) {
        clearInterval(countdown);
        resendBtn.disabled = false;
        resendText.style.display = 'inline';
        resendCountdown.style.display = 'none';
      }
    }, 1000);

    // Auto-focus kotak pertama
    boxes[0].focus();
  </script>
@endsection
