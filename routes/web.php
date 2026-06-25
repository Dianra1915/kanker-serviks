<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\KonsultasiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rute yang butuh Login (Auth)
Route::middleware('auth')->group(function() {
    
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/about', function () { return view('about'); })->name('about');
    Route::get('/blank', function () { return view('blank'); })->name('blank');

    // AKSES BERSAMA (Admin & Pasien)
    Route::get('/konsultasi', [KonsultasiController::class, 'index'])->name('konsultasi.index');
    // TAMBAHAN BARU: Rute untuk menyimpan jawaban 1 per 1 (Ya/Tidak) ke session
    Route::post('/konsultasi/jawab', [KonsultasiController::class, 'simpanJawaban'])->name('konsultasi.jawab');
    // UBAH: dari Route::post menjadi Route::get karena proses diagnosa sekarang otomatis terpanggil/redirect saat pertanyaan habis
    Route::get('/konsultasi/proses', [KonsultasiController::class, 'prosesDiagnosa'])->name('konsultasi.proses');
    Route::get('/konsultasi/hasil/{id}', [KonsultasiController::class, 'show'])->name('konsultasi.hasil');
    Route::get('/riwayat', [KonsultasiController::class, 'riwayat'])->name('riwayat');
    // ---> TAMBAHKAN KODE INI UNTUK FITUR KEMBALI <---
    Route::get('/konsultasi/kembali', [KonsultasiController::class, 'kembali'])->name('konsultasi.kembali');

    // Tambahkan rute Cetak PDF di sini agar bisa diakses Pasien & Admin
    Route::get('/konsultasi/cetak/{id}', [KonsultasiController::class, 'cetakPdf'])->name('konsultasi.cetak');
    Route::get('/riwayat/cetak-semua', [KonsultasiController::class, 'cetakSemua'])->name('riwayat.cetak_semua');
    
    // KHUSUS ADMIN / DOKTER
    Route::middleware('role:admin')->group(function () {
        Route::resource('gejala', GejalaController::class);
        Route::resource('jenis', JenisController::class);
        Route::resource('rules', RuleController::class);
        
        // Admin bisa menghapus riwayat hasil konsultasi
        Route::delete('/konsultasi/{id}', [KonsultasiController::class, 'destroy'])->name('konsultasi.destroy');        
    });
});

// Rute kustom untuk memproses reset password tanpa validasi email Fortify
Route::post('/proses-reset-password', [\App\Actions\Fortify\ResetUserPassword::class, 'prosesResetKustom'])->name('password.update.custom');