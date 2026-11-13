<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keterangan_sakit', function (Blueprint $table): void {
            $table->id('id_surat_keterangan_sakit');
            $table->foreignId('id_kunjungan')->constrained('kunjungan', 'id_kunjungan')->cascadeOnDelete();
            $table->foreignId('id_dokter')->nullable()->constrained('dokter', 'id_dokter')->nullOnDelete();
            $table->string('nomor_surat', 40)->unique();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('diagnosa_ringkas', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->index(['id_kunjungan', 'tanggal_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keterangan_sakit');
    }
};
