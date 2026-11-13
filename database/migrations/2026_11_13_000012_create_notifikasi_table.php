<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table): void {
            $table->uuid('id_notifikasi')->primary();
            $table->foreignId('id_pengguna_penerima')->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->string('jenis_notifikasi', 40)->index();
            $table->string('judul_notifikasi', 190);
            $table->text('pesan_notifikasi');
            $table->json('data_tambahan')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('dibuat_pada')->nullable();

            $table->index(['id_pengguna_penerima', 'dibaca_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
