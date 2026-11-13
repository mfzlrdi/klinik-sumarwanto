<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table): void {
            $table->id('id_pasien');
            $table->string('nomor_rekam_medis', 30)->unique();
            $table->string('nama_lengkap', 120);
            $table->string('nomor_identitas', 40)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->string('nomor_telepon', 30)->nullable();
            $table->string('alamat_email', 190)->nullable();
            $table->text('catatan_administratif')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->index('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
