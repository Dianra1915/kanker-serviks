@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Profile') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Blok Notifikasi Status Sukses --}}
    @if (session('status') === 'password-updated')
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-left-success" role="alert">
            <i class="fas fa-check-circle mr-2"></i> Password berhasil diubah!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Blok Notifikasi Status Error Validasi --}}
    @if ($errors->updatePassword->any())
        <div class="alert alert-danger border-left-danger shadow-sm" role="alert">
            <ul class="pl-4 my-0">
                @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-4 order-lg-2">

            <div class="card shadow mb-4">
                <div class="card-profile-image mt-4">
                    <figure class="rounded-circle avatar avatar font-weight-bold" style="font-size: 60px; height: 180px; width: 180px;" data-initial="{{ Auth::user()->username[0] }}"></figure>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h5 class="font-weight-bold">{{  Auth::user()->fullName }}</h5>
                                <p>Administrator</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card-profile-stats">
                                <span class="heading">22</span>
                                <span class="description">Friends</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-profile-stats">
                                <span class="heading">10</span>
                                <span class="description">Photos</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-profile-stats">
                                <span class="heading">89</span>
                                <span class="description">Comments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">My Account</h6>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <input type="hidden" name="_method" value="PUT">

                        <h6 class="heading-small text-muted mb-4">User information</h6>

                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="username">Username<span class="small text-danger">*</span></label>
                                        <input type="text" id="username" class="form-control" name="username" placeholder="username" value="{{ old('username', Auth::user()->username) }}">
                                    </div>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="phone_number">No Handphone<span class="small text-danger">*</span></label>
                                        <input type="phone_number" id="phone_number" class="form-control" name="phone_number" placeholder="example@example.com" value="{{ old('phone_number', Auth::user()->phone_number) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- 1. Input Password Lama (Current Password) --}}
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="current_password">Current password</label>
                                        <div class="position-relative">
                                            <input type="password" id="current_password" class="form-control" name="current_password" placeholder="Current password">
                                            <span class="position-absolute" style="right: 20px; top: 10px; cursor: pointer;" onclick="toggleProfilePassword('current_password', 'eye-current')">
                                                <i id="eye-current" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- 2. Input Password Baru (New Password) --}}
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="new_password">New password</label>
                                        <div class="position-relative">
                                            <input type="password" id="new_password" class="form-control" name="new_password" placeholder="New password">
                                            <span class="position-absolute" style="right: 20px; top: 10px; cursor: pointer;" onclick="toggleProfilePassword('new_password', 'eye-new')">
                                                <i id="eye-new" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- 3. Input Konfirmasi Password Baru (Confirm Password) --}}
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="confirm_password">Confirm password</label>
                                        <div class="position-relative">
                                            <input type="password" id="confirm_password" class="form-control" name="password_confirmation" placeholder="Confirm password">
                                            <span class="position-absolute" style="right: 20px; top: 10px; cursor: pointer;" onclick="toggleProfilePassword('confirm_password', 'eye-confirm')">
                                                <i id="eye-confirm" class="fas fa-eye text-gray-600"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

{{-- Script JavaScript Khusus untuk Toggle Password di Halaman Profil --}}
<script>
function toggleProfilePassword(inputId, iconId) {
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
