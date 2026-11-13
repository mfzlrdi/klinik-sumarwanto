<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rincian_resep', function (Blueprint $table): void {
            $table->id('id_rincian_resep');
            $table->foreignId('id_resep')->constrained('resep', 'id_resep')->cascadeOnDelete();
            $table->foreignId('id_obat')->nullable()->constrained('obat', 'id_obat')->nullOnDelete();
            $table->string('nama_obat_saat_diresepkan', 190);
            $table->string('kekuatan_saat_diresepkan', 60)->nullable();
            $table->string('bentuk_sediaan_saat_diresepkan', 60)->nullable();
            $table->string('dosis', 80)->nullable();
            $table->string('frekuensi_pemberian', 120)->nullable();
            $table->string('cara_pemberian', 120)->nullable();
            $table->unsignedInteger('lama_penggunaan_hari')->nullable();
            $table->unsignedInteger('jumlah_diresepkan')->default(0);
            $table->string('satuan_jumlah', 30)->nullable();
            $table->text('petunjuk_penggunaan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rincian_resep');
    }
};
