<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 4. Tabel Konsultasi (Input gejala oleh user)
        Schema::create('konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
            $table->double('nilai_cf_user'); // Keyakinan user (0-1)
            $table->timestamps();
        });

        // 5. Tabel Hasil Konsultasi (Diagnosa akhir)
        Schema::create('hasil_konsultasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('jenis_id')->constrained('jenis')->onDelete('cascade');
            $table->double('total_cf');
            $table->timestamp('tgl_konsultasi')->useCurrent();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('hasil_konsultasi');
        Schema::dropIfExists('konsultasi');
    }
};