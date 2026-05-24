<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        Validator::make($input, [
            'username' => [
                'required', 
                'string', 
                'max:255', 
                Rule::unique('users')->ignore($user->id),
            ],

            'phone_number' => [
                'required',
                'string',
                'max:15',
                Rule::unique('users')->ignore($user->id),
            ],
        ], [
            'username.unique' => 'Username ini sudah digunakan oleh pengguna lain.',
            'phone_number.unique' => 'Nomor handphone ini sudah digunakan oleh pengguna lain.',
        ])->validateWithBag('updateProfileInformation');

        // Lakukan penyimpanan data baru langsung ke database
        $user->forceFill([
            'username' => $input['username'],
            'phone_number' => $input['phone_number'],
        ])->save();
    }
}