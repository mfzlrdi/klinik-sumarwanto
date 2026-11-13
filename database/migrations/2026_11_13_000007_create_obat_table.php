<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('obat', function (Blueprint $table): void {
            $table->id('id_obat');
            $table->string('nama_obat', 190);
            $table->string('kekuatan_obat', 60)->nullable();
            $table->string('bentuk_sediaan', 60)->nullable();
            $table->string('satuan_obat', 30)->default('tablet');
            $table->boolean('status_aktif')->default(true)->index();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();

            $table->unique(['nama_obat', 'kekuatan_obat', 'bentuk_sediaan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
