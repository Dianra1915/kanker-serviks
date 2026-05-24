<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gejala extends Model
{
    // Nama tabel di database
    protected $table = 'gejala';

    // Field yang boleh diisi melalui form
    protected $fillable = ['kode_gejala', 'nama_gejala'];
}