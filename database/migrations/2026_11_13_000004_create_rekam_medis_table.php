<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table): void {
            $table->id('id_rekam_medis');
            $table->foreignId('id_kunjungan')->unique()->constrained('kunjungan', 'id_kunjungan')->cascadeOnDelete();
            $table->foreignId('id_dokter')->nullable()->constrained('dokter', 'id_dokter')->nullOnDelete();
            $table->text('hasil_pemeriksaan')->nullable();
            $table->json('tanda_vital')->nullable();
            $table->text('catatan_klinis')->nullable();
            $table->string('status_rekam_medis', 20)->default('draf');
            $table->timestamp('difinalisasi_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
