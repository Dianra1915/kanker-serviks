<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HasilKonsultasi extends Model 
{
    protected $table = 'hasil_konsultasi';
    protected $fillable = ['user_id', 'jenis_id', 'total_cf', 'tgl_konsultasi'];

    public function user() { return $this->belongsTo(User::class); }
    public function jenis() { return $this->belongsTo(Jenis::class, 'jenis_id'); }
}
