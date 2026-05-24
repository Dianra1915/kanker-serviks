@extends('layouts.auth')

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Login') }}</h1>
                                </div>

                                {{-- Pop-up Notifikasi Berhasil/Gagal --}}
                                @if (session('success_login'))
                                    <div class="alert alert-success border-left-success text-center shadow-sm">
                                        {{ session('success_login') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger border-left-danger shadow-sm" role="alert">
                                        <ul class="pl-4 my-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="user mt-3">
                                    @csrf

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" placeholder="Masukkan Username Anda" value="{{ old('username') }}" required autofocus>
                                    </div>

                                    <div class="form-group position-relative">
                                        <input type="password" id="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="Masukkan Password" required>
                                        <span class="position-absolute" style="right: 20px; top: 12px; cursor: pointer;" onclick="togglePassword()">
                                            <i id="eye-icon" class="fas fa-eye text-gray-600"></i>
                                        </span>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block shadow">
                                        Login Sekarang
                                    </button>
                                </form>

                                <hr>
                                @if (Route::has('password.request'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('password.request') }}">
                                            {{ __('Lupa Password?') }}
                                        </a>
                                    </div>
                                @endif

                                @if (Route::has('register'))
                                    <div class="text-center">
                                        <a class="small" href="{{ route('register') }}">{{ __('Belum punya akun? Silahkan Registrasi ') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordField = document.getElementById("password");
    const eyeIcon = document.getElementById("eye-icon");
    if (passwordField.type === "password") {
        passwordField.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passwordField.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>
@endsection