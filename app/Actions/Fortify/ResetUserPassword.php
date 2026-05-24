<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    /**
     * Memenuhi kontrak asli Fortify (tetap wajib ada agar tidak error)
     */
    public function reset($user, array $input)
    {
        // Fungsi bawaan Fortify ini dikosongkan karena kita bypass rutenya
    }

    /**
     * FUNGSI UTAMA: Menangani reset kustom Username + No HP tanpa intervensi Email Fortify
     */
    public function prosesResetKustom(Request $request)
    {
        // 1. Jalankan Validasi format input form
        Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'password' => [
                'required', 
                'string', 
                'min:6', 
                'confirmed', 
                'regex:/^[A-Z]/', // Aturan dosen: wajib diawali huruf kapital
            ],
        ], [
            'username.required' => 'Username wajib diisi.',
            'phone_number.required' => 'Nomor Handphone wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.regex' => 'Password baru harus diawali dengan huruf kapital.',
            'password.min' => 'Password baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ])->validate();

        // 2. Cari kecocokan data pasien di database berdasarkan Username DAN No HP
        $user = User::where('username', $request->username)
                    ->where('phone_number', $request->phone_number)
                    ->first();

        // Jika tidak ditemukan kecocokan
        if (!$user) {
            throw ValidationException::withMessages([
                'username' => ['Kombinasi Username dan Nomor Handphone tidak ditemukan di sistem.'],
            ]);
        }

        // 3. Update password baru menggunakan Hash bcrypt Laravel
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        // 4. Set notifikasi sukses untuk ditampilkan di halaman login
        session()->flash('success_login', 'Password berhasil diperbarui! Silakan login dengan password baru Anda.');

        // Redirect secara bersih ke halaman login utama
        return redirect()->route('login');
    }
}