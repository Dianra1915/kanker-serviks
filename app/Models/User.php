<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Properti yang dapat diisi secara massal.
     */
    protected $fillable = [
        'username', 
        'phone_number', 
        'password', 
        'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Karena name & last_name digabung menjadi username, accessor ini bisa disederhanakan
    public function getFullNameAttribute()
    {
        return $this->username;
    }
}