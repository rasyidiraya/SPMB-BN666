@extends('welcome')

@section('content')
  <style>
    .auth-wrapper {
      min-height: 100vh;
      padding-bottom: 100px;
    }
  </style>

  <div class="auth-wrapper">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card auth-card shadow-lg">
            <div class="auth-header">
              <i class="bi bi-shield-lock-fill"></i>
              <h2 class="fw-bold mb-2 ucapan">Selamat Datang!</h2>
              <p class="mb-0 opacity-75">Sistem Penerimaan Mahasiswa Baru</p>
            </div>

            <div class="card-body p-4 p-md-5">
              @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first() }}
                </div>
              @endif

              <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-4">
                  <label class="form-label fw-semibold">Email</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                  </div>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Password</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                      <i class="bi bi-eye-slash-fill" id="togglePasswordIcon"></i>
                    </button>
                  </div>
                </div>

                <button type="submit" class="btn btn-gradient w-100 mb-3">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                </button>

                {{-- Divider --}}
                <div class="d-flex align-items-center my-3">
                  <hr class="flex-grow-1">
                  <span class="px-3 text-muted small">atau</span>
                  <hr class="flex-grow-1">
                </div>

                {{-- Tombol Login dengan Google --}}
                <a href="{{ route('auth.google') }}"
                   class="btn btn-outline-dark w-100 mb-4 d-flex align-items-center justify-content-center gap-2">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48" aria-hidden="true">
                    <path fill="#EA4335" d="M24 9.5c3.5 0 6.6 1.2 9 3.2l6.7-6.7C35.8 2.5 30.3 0 24 0 14.6 0 6.6 5.4 2.6 13.3l7.8 6C12.4 13 17.8 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.5 24.5c0-1.6-.1-3.1-.4-4.5H24v8.5h12.7c-.6 3-2.3 5.5-4.8 7.2l7.5 5.8C43.7 37.3 46.5 31.3 46.5 24.5z"/>
                    <path fill="#FBBC05" d="M10.4 28.7A14.4 14.4 0 0 1 9.5 24c0-1.6.3-3.2.8-4.7l-7.8-6A23.9 23.9 0 0 0 0 24c0 3.9.9 7.5 2.6 10.7l7.8-6z"/>
                    <path fill="#34A853" d="M24 48c6.3 0 11.6-2.1 15.4-5.7l-7.5-5.8c-2.1 1.4-4.8 2.3-7.9 2.3-6.2 0-11.5-4.2-13.4-9.9l-7.8 6C6.6 42.6 14.6 48 24 48z"/>
                    <path fill="none" d="M0 0h48v48H0z"/>
                  </svg>
                  <span>Login dengan Google</span>
                </a>

                <a href="{{ route('home') }}" class="btn btn-outline-secondary w-100 mb-4">
                  <i class="bi bi-arrow-left me-2"></i>Kembali ke Beranda
                </a>

                <div class="text-center">
                  <p class="text-muted mb-0">Belum punya akun?
                    <a href="{{ route('pendaftar.register') }}" class="fw-semibold text-decoration-none"
                      style="color: #0074b7;">Daftar di sini</a>
                  </p>
                </div>
              </form>
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
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const password = document.getElementById('password');
      const icon = document.getElementById('togglePasswordIcon');

      if (togglePassword && password && icon) {
        togglePassword.addEventListener('click', function() {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          icon.classList.toggle('bi-eye-fill');
          icon.classList.toggle('bi-eye-slash-fill');
        });
      }
    });
  </script>


@endsection