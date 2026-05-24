<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $table = 'rules';
    protected $fillable = ['gejala_id', 'jenis_id', 'mb', 'md'];

    // Relasi ke Gejala
    public function gejala() {
        return $this->belongsTo(Gejala::class, 'gejala_id');
    }

    // Relasi ke Jenis Kanker
    public function jenis() {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }
}
