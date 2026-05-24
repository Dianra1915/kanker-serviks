<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\RegisterResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //KUNCI UTAMA: Daftarkan interupsi respon registrasi di sini agar dieksekusi paling awal
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    // Pastikan membersihkan sesi login otomatis jika Laravel telanjur membuatnya di background
                    auth()->logout();
                    
                    // Alihkan paksa ke halaman login
                    return redirect()->route('login')->with('success_login', 'Registrasi berhasil! Silakan masuk menggunakan akun baru Anda.');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        // TRIK UTAMA: Cegat request password update sebelum memicu error "Unknown column email"
        if (request()->is('reset-password') || request()->is('forgot-password') || request()->routeIs('password.update')) {
            // Ambil nomor handphone yang diinput user di form
            $phone = request()->input('phone_number');
            
            if ($phone) {
                // Sisi internal Laravel Fortify mencari input bernama 'email'.
                // Kita isi input 'email' tersebut dengan nilai 'phone_number' secara paksa di sistem memori
                request()->merge(['email' => $phone]);
            }
        }
    }
}
