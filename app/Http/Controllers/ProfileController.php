<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        // Penyesuaian Validasi ke Username, Phone_number, dan Aturan Password Baru
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::user()->id,
            'phone_number' => 'required|string|max:15|unique:users,phone_number,' . Auth::user()->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => [
                'nullable',
                'string',
                'min:6', // Minimal 6 karakter sesuai syarat register
                'required_with:current_password',
                'regex:/^[A-Z]/', // Wajib diawali huruf kapital sesuai syarat register
            ],
            'password_confirmation' => 'nullable|required_with:new_password|same:new_password'
        ], [
            // Pesan error kustom agar pengguna paham aturan ganti password
            'new_password.regex' => 'Password baru harus diawali dengan huruf kapital.',
            'new_password.min' => 'Password baru minimal berukuran 6 karakter.',
            'username.unique' => 'Username ini sudah digunakan oleh pengguna lain.',
            'phone_number.unique' => 'Nomor handphone ini sudah digunakan oleh pengguna lain.',
        ]);

        $user = User::findOrFail(Auth::user()->id);
        
        // Simpan data identitas baru
        $user->username = $request->input('username');
        $user->phone_number = $request->input('phone_number');

        // Logika Penggantian Password jika diisi
        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = Hash::make($request->input('new_password'));
            } else {
                // Jika password lama salah, kembalikan dengan pesan error pop-up/alert session
                return redirect()->back()->withErrors(['current_password' => 'Password lama yang Anda masukkan salah.'])->withInput();
            }
        }

        $user->save();

        // Kembalikan ke halaman dengan notifikasi sukses ganti profil
        return redirect()->route('profile')->with('success', 'Profil dan informasi akun Anda berhasil diperbarui!');
    }
}