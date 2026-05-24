<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.forgot-password', ['request' => $request]);
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify');
        });

        // 1. KUSTOMISASI AUTENTIKASI: Validasi pesan error spesifik saat Login
        Fortify::authenticateUsing(function ($request) {
            $user = User::where('username', $request->username)->first();

            // Jika Username tidak ditemukan
            if (!$user) {
                throw ValidationException::withMessages([
                    'username' => ['username yang dimasukkan salah, silahkan coba lagi.'],
                ]);
            }

            // Jika Username ada tapi password salah
            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['password yang dimasukkan salah, silahkan coba lagi.'],
                ]);
            }

            // Berhasil Login
            session()->flash('success_login', 'Anda berhasil login');
            return $user;
        });

    }
}