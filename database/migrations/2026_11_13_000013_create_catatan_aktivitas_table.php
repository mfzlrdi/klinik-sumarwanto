<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_aktivitas', function (Blueprint $table): void {
            $table->id('id_log_aktivitas');
            $table->foreignId('id_pengguna')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->string('jenis_aktivitas', 40)->index();
            $table->string('nama_entitas', 60)->index();
            $table->unsignedBigInteger('id_data_terkait')->nullable();
            $table->json('ringkasan_perubahan')->nullable();
            $table->string('alamat_ip', 45)->nullable();
            $table->timestamp('dibuat_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_aktivitas');
    }
};
