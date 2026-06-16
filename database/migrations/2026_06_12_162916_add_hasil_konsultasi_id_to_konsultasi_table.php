<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('konsultasi', function (Blueprint $table) {
            // Menambahkan kolom foreign key ke tabel hasil_konsultasi
            $table->foreignId('hasil_konsultasi_id')->nullable()->after('user_id')->constrained('hasil_konsultasi')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('konsultasi', function (Blueprint $table) {
            $table->dropForeign(['hasil_konsultasi_id']);
            $table->dropColumn('hasil_konsultasi_id');
        });
    }
};
