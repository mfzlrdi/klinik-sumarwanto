<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table): void {
            $table->id('id_dokter');
            $table->foreignId('id_pengguna')->unique()->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->string('nomor_sip', 50)->nullable();
            $table->string('nomor_telepon_profesional', 30)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
