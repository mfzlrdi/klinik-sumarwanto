<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rincian_penyerahan_obat', function (Blueprint $table): void {
            $table->id('id_rincian_penyerahan_obat');
            $table->foreignId('id_pemenuhan_resep')->constrained('pemenuhan_resep', 'id_pemenuhan_resep')->cascadeOnDelete();
            $table->foreignId('id_rincian_resep')->constrained('rincian_resep', 'id_rincian_resep')->cascadeOnDelete();
            $table->unsignedInteger('jumlah_diserahkan')->default(0);
            $table->foreignId('id_pengguna_penyerah')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->timestamp('diserahkan_pada')->nullable();
            $table->text('catatan_penyerahan')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rincian_penyerahan_obat');
    }
};
