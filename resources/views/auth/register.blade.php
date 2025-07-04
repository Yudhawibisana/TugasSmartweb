@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary">Daftarkan Dirimu dan Temukan Teman Belajarmu!</h4>
            </div>

            <div class="d-grid mb-3">
                <a href="{{ route('login.google') }}" class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                    <img src="{{ asset('front/assets/google.png') }}" width="20" height="20">
                    Daftar dengan Google
                </a>
            </div>

            <div class="text-center my-2">Atau</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap (sesuai KTP)</label>
                    <input id="name" type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required>
                        <span class="input-group-text">
                            <i class="bi bi-eye-slash toggle-password" data-target="password" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    <small id="password-hint" class="text-muted">
                        Gunakan 8 atau lebih karakter, dengan perpaduan huruf, angka & simbol.
                    </small>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                    <div class="input-group">
                        <input id="password-confirm" type="password"
                               class="form-control"
                               name="password_confirmation" required>
                        <span class="input-group-text">
                            <i class="bi bi-eye-slash toggle-password" data-target="password-confirm" style="cursor: pointer;"></i>
                        </span>
                    </div>
                    <small id="confirm-hint" class="text-muted">
                        Harus sama dengan password yang dimasukkan sebelumnya.
                    </small>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" required>
                    <label class="form-check-label">
                        Dengan ini saya menyatakan bahwa seluruh data dan/atau informasi yang saya sampaikan adalah benar
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Daftar</button>
                </div>

                <div class="text-center mt-3">
                    Sudah mempunyai akun? <a href="{{ route('login') }}">Login disini</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleIcons = document.querySelectorAll('.toggle-password');
        toggleIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const target = document.querySelector(`#${this.dataset.target}`);
                const type = target.getAttribute('type') === 'password' ? 'text' : 'password';
                target.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });

        // Password validation
        const passwordInput = document.getElementById('password');
        const passwordHint = document.getElementById('password-hint');

        passwordInput.addEventListener('input', validatePasswordFields);

        // Confirm password validation
        const confirmInput = document.getElementById('password-confirm');
        const confirmHint = document.getElementById('confirm-hint');

        confirmInput.addEventListener('input', validatePasswordFields);

        function validatePasswordFields() {
            const passwordValue = passwordInput.value;
            const confirmValue = confirmInput.value;

            // Validate password strength
            const isValidPassword = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{8,}$/.test(passwordValue);
            if (!isValidPassword) {
                passwordInput.classList.add('is-invalid');
                passwordHint.classList.remove('text-muted');
                passwordHint.classList.add('text-danger');
            } else {
                passwordInput.classList.remove('is-invalid');
                passwordHint.classList.remove('text-danger');
                passwordHint.classList.add('text-muted');
            }

            // Validate confirm password
            if (confirmValue && confirmValue !== passwordValue) {
                confirmInput.classList.add('is-invalid');
                confirmHint.classList.remove('text-muted');
                confirmHint.classList.add('text-danger');
            } else {
                confirmInput.classList.remove('is-invalid');
                confirmHint.classList.remove('text-danger');
                confirmHint.classList.add('text-muted');
            }
        }
    });
</script>
@endpush
