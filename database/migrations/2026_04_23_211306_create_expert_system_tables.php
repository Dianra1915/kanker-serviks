<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // 1. Tabel Gejala
        Schema::create('gejala', function (Blueprint $table) {
            $table->id();
            $table->string('kode_gejala')->unique();
            $table->string('nama_gejala');
            $table->timestamps();
        });

        // 2. Tabel Jenis (Menggantikan Stadium)
        Schema::create('jenis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_jenis')->unique();
            $table->string('nama_jenis');
            $table->text('solusi');
            $table->timestamps();
        });

        // 3. Tabel Rules (Tempat MB & MD berada)
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_id')->constrained('jenis')->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
            $table->double('mb'); // Measure of Belief
            $table->double('md'); // Measure of Disbelief
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('rules');
        Schema::dropIfExists('jenis');
        Schema::dropIfExists('gejala');
    }
};