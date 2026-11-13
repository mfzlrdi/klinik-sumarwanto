<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemenuhan_resep', function (Blueprint $table): void {
            $table->id('id_pemenuhan_resep');
            $table->foreignId('id_resep')->unique()->constrained('resep', 'id_resep')->cascadeOnDelete();
            $table->string('status_pemenuhan', 30)->default('menunggu')->index();
            $table->foreignId('id_pengguna_penanggung_jawab')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->text('catatan_pemenuhan')->nullable();
            $table->timestamp('mulai_diproses_pada')->nullable();
            $table->timestamp('selesai_diproses_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemenuhan_resep');
    }
};
