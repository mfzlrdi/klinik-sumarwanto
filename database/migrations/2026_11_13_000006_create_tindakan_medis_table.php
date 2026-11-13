<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tindakan_medis', function (Blueprint $table): void {
            $table->id('id_tindakan_medis');
            $table->foreignId('id_rekam_medis')->constrained('rekam_medis', 'id_rekam_medis')->cascadeOnDelete();
            $table->string('nama_tindakan', 255);
            $table->text('catatan_tindakan')->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tindakan_medis');
    }
};
