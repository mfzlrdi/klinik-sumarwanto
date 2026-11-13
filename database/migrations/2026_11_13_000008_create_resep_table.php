<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep', function (Blueprint $table): void {
            $table->id('id_resep');
            $table->foreignId('id_kunjungan')->constrained('kunjungan', 'id_kunjungan')->cascadeOnDelete();
            $table->foreignId('id_rekam_medis')->nullable()->constrained('rekam_medis', 'id_rekam_medis')->nullOnDelete();
            $table->foreignId('id_dokter')->nullable()->constrained('dokter', 'id_dokter')->nullOnDelete();
            $table->string('nomor_resep', 40)->unique();
            $table->string('status_resep', 20)->default('draf')->index();
            $table->text('catatan_resep')->nullable();
            $table->timestamp('difinalisasi_pada')->nullable();
            $table->timestamp('dibatalkan_pada')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep');
    }
};
