<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input)
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class, 'username')],
            'phone_number' => ['required', 'string', 'max:15', Rule::unique(User::class, 'phone_number')],
            'password' => [
                'required', 
                'string', 
                'min:6', 
                'confirmed', 
                'regex:/^[A-Z]/', // Memastikan huruf pertama kapital
            ],
        ], [
            'password.regex' => 'Password harus diawali dengan huruf kapital.',
            'password.min' => 'Password minimal berukuran 6 karakter.',
            'username.unique' => 'Username ini sudah terdaftar.',
            'phone_number.unique' => 'Nomor handphone ini sudah terdaftar.',
        ])->validate();

        return User::create([
            'username' => $input['username'],
            'phone_number' => $input['phone_number'],
            'role' => 'pasien',
            'password' => Hash::make($input['password']),
        ]);
    }
}