<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosa', function (Blueprint $table): void {
            $table->id('id_diagnosa');
            $table->foreignId('id_rekam_medis')->constrained('rekam_medis', 'id_rekam_medis')->cascadeOnDelete();
            $table->string('kode_diagnosa', 20)->nullable();
            $table->string('nama_diagnosa', 255);
            $table->string('jenis_diagnosa', 20)->default('utama');
            $table->text('catatan_diagnosa')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosa');
    }
};
