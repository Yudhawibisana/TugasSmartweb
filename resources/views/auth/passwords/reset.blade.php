@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="text-center mb-4">
                <h4 class="fw-bold text-primary">Reset Password</h4>
            </div>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ $email ?? old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
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
                    <label for="password-confirm" class="form-label">Konfirmasi Password Baru</label>
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

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </div>

                <div class="text-center mt-3">
                    Sudah ingat akun Anda? <a href="{{ route('login') }}">Login di sini</a>
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
        const confirmInput = document.getElementById('password-confirm');
        const confirmHint = document.getElementById('confirm-hint');

        function validatePasswordFields() {
            const passwordValue = passwordInput.value;
            const confirmValue = confirmInput.value;

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

        passwordInput.addEventListener('input', validatePasswordFields);
        confirmInput.addEventListener('input', validatePasswordFields);
    });
</script>
@endpush