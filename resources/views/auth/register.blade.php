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
                                    <h1 class="h4 text-gray-900 mb-4">Registrasi Akun Baru</h1>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger border-left-danger shadow-sm" role="alert">
                                        <ul class="pl-4 my-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('register') }}" class="user mt-3">
                                    @csrf

                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" name="username" placeholder="Nama Lengkap / Username" value="{{ old('username') }}" required>
                                    </div>
                            
                                    <div class="form-group">
                                        <input type="text" 
                                            name="phone_number" 
                                            class="form-control form-control-user" 
                                            placeholder="Nomor Handphone Aktif (Contoh: 081234567890)" 
                                            required 
                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                            value="{{ old('phone_number') }}">
                                    </div>

                                    <div class="form-group">
                                        {{-- Input Password Utama --}}
                                        <div class="position-relative mb-3">
                                            <input type="password" id="password" class="form-control form-control-user" name="password" placeholder="Password (Diawali Huruf Kapital)" required>
                                            <span class="position-absolute" style="right: 25px; top: 12px; cursor: pointer;" onclick="togglePassword('password', 'eye-1')">
                                                <i id="eye-1" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>

                                        {{-- Input Konfirmasi Password --}}
                                        <div class="position-relative">
                                            <input type="password" id="password_confirmation" class="form-control form-control-user" name="password_confirmation" placeholder="Ulangi Password" required>
                                            <span class="position-absolute" style="right: 25px; top: 12px; cursor: pointer;" onclick="togglePassword('password_confirmation', 'eye-2')">
                                                <i id="eye-2" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-user btn-block shadow">
                                        Daftar Akun
                                    </button>
                                </form>

                                <hr>
                                <div class="text-center">
                                    <a class="small" href="{{ route('login') }}">Sudah punya akun? Masuk di sini</a>
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
function togglePassword(inputId, iconId) {
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