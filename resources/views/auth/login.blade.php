@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="row shadow rounded overflow-hidden">

                    <!-- Left Side - Banner -->
                    <div class="col-md-6 p-0 d-none d-md-block">
                        <img src="{{ asset('front/assets/banner_login.jpg') }}" alt="Login Banner" class="img-fluid h-100 w-100 object-fit-cover">
                    </div>

                    <!-- Right Side - Login Form -->
                    <div class="col-md-6 p-5 bg-login">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold">Masuk untuk Melanjutkan Belajar</h4>
                        </div>

                        @if ($errors->has('email'))
                            @if ($errors->first('email') === 'These credentials do not match our records.')
                                <div class="alert alert-danger">
                                    Email atau password salah.
                                </div>
                            @endif
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email"
                                       class="form-control @error('email') is-invalid @enderror" name="email"
                                       value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    @if ($message !== 'These credentials do not match our records.')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @endif
                                @enderror
                            </div>

                            <div class="mb-3 position-relative">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    @php
                                        $loginError = $errors->has('email') && $errors->first('email') === 'These credentials do not match our records.';
                                    @endphp

                                    <input id="password" type="password"
                                        class="form-control {{ $loginError ? 'is-invalid' : '' }} @error('password') is-invalid @enderror"
                                        name="password" required>
                                    <span class="input-group-text">
                                        <i class="bi bi-eye-slash toggle-password" data-target="password" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                @error('password')
                                    @if ($message !== 'These credentials do not match our records.')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @endif
                                @enderror
                            </div>

                            <div>
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ingat saya
                                </label>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">
                                    Masuk
                                </button>
                            </div>

                            <div class="text-center my-2">Atau</div>

                            <div class="d-grid mb-3">
                                <button type="button"
                                        onclick="window.location.href='{{ route('login.google') }}'"
                                        class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2">
                                    <img src="{{ asset('front/assets/google.png') }}" width="20" height="20">
                                    Masuk dengan Google
                                </button>
                            </div>

                            @if (Route::has('register'))
                                <div class="text-center mt-3">
                                    Baru di StudyGroup?
                                    <a href="{{ route('register') }}">Daftar disini</a>
                                </div>
                            @endif
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

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
    });
</script>
@endpush
@endsection
