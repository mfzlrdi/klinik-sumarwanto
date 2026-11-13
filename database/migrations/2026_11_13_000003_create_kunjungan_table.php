<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table): void {
            $table->id('id_kunjungan');
            $table->foreignId('id_pasien')->constrained('pasien', 'id_pasien')->cascadeOnDelete();
            $table->foreignId('id_dokter')->nullable()->constrained('dokter', 'id_dokter')->nullOnDelete();
            $table->date('tanggal_kunjungan');
            $table->unsignedInteger('nomor_antrean');
            $table->text('keluhan_awal')->nullable();
            $table->string('status_kunjungan', 30)->default('menunggu')->index();
            $table->foreignId('id_pengguna_pendaftar')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->text('catatan_administratif')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            // Nomor antrean unik per tanggal operasional agar tidak terjadi duplikasi.
            $table->unique(['tanggal_kunjungan', 'nomor_antrean']);
            $table->index(['tanggal_kunjungan', 'status_kunjungan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
