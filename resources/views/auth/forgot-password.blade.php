@extends('layouts.auth')

@section('main-content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-password-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Reset Password Akun</h1>
                                    <p class="small text-muted mb-4">Masukkan Username dan Nomor Handphone Anda yang telah terdaftar untuk membuat password baru.</p>
                                </div>

                                {{-- Menampilkan Error Validasi --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger border-left-danger shadow-sm" role="alert">
                                        <ul class="pl-4 my-2">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Form Action mengarah ke rute kustom --}}
                                <form method="POST" action="{{ route('password.update.custom') }}" class="user">
                                    @csrf

                                    {{-- Input 1: Username --}}
                                    <div class="form-group mb-3">
                                        <label class="text-dark small font-weight-bold">Username Akun</label>
                                        <input type="text" class="form-control form-control-user" name="username" placeholder="Masukkan Username Anda" value="{{ old('username') }}" required autofocus>
                                    </div>

                                    {{-- Input 2: Nomor Handphone --}}
                                    <div class="form-group mb-3">
                                        <label class="text-dark small font-weight-bold">Nomor Handphone</label>
                                        <input type="text" class="form-control form-control-user" name="phone_number" placeholder="Masukkan Nomor HP Terdaftar" value="{{ old('phone_number') }}" required>
                                    </div>

                                    <hr>

                                    {{-- Input 3: Password Baru + Icon Mata --}}
                                    <div class="form-group mb-3">
                                        <label class="text-dark small font-weight-bold">Password Baru</label>
                                        <div class="position-relative">
                                            <input type="password" id="password" class="form-control form-control-user" name="password" placeholder="Minimal 6 karakter & Huruf Kapital di awal" required style="padding-right: 45px;">
                                            <span class="position-absolute" style="right: 20px; top: 12px; cursor: pointer; z-index: 10;" onclick="toggleForgotPassword('password', 'eye-1')">
                                                <i id="eye-1" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Input 4: Konfirmasi Password Baru + Icon Mata --}}
                                    <div class="form-group mb-4">
                                        <label class="text-dark small font-weight-bold">Konfirmasi Password Baru</label>
                                        <div class="position-relative">
                                            <input type="password" id="password_confirmation" class="form-control form-control-user" name="password_confirmation" placeholder="Ulangi Password Baru" required style="padding-right: 45px;">
                                            <span class="position-absolute" style="right: 20px; top: 12px; cursor: pointer; z-index: 10;" onclick="toggleForgotPassword('password_confirmation', 'eye-2')">
                                                <i id="eye-2" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-user btn-block shadow">
                                            Perbarui Password Sekarang
                                        </button>
                                    </div>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Batal dan Kembali ke Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleForgotPassword(inputId, iconId) {
    const passwordField = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
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