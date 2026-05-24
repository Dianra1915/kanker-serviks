<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => [
                'required', 
                'string', 
                'min:6', 
                'confirmed', 
                'regex:/^[A-Z]/', // Aturan wajib diawali Huruf Kapital
            ],
        ], [
            // Pesan Kustom Bahasa Indonesia untuk Validasi Password Baru
            'password.required' => 'Kolom password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok / tidak sama.',
            'password.regex' => 'Password baru harus diawali dengan huruf kapital.',
            'current_password.required' => 'Kolom password lama wajib diisi.',
        ])->after(function ($validator) use ($user, $input) {
            // Validasi khusus: Cek apakah password lama yang dimasukkan cocok dengan database
            if (!empty($input['current_password']) && !Hash::check($input['current_password'], $user->password)) {
                $validator->errors()->add('current_password', 'Password lama yang Anda masukkan salah.');
            }
        })->validateWithBag('updatePassword');

        // Jika semua validasi di atas lolos, simpan password baru
        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();

        // Mengirimkan flash session status khusus untuk dibaca di halaman view profil
        session()->flash('status', 'password-updated');
    }
}