<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Konsultasi extends Model 
{
    protected $table = 'konsultasi';
    protected $fillable = ['user_id', 'hasil_konsultasi_id', 'gejala_id', 'nilai_cf_user'];
    
    public function gejala() { return $this->belongsTo(Gejala::class); }
}
