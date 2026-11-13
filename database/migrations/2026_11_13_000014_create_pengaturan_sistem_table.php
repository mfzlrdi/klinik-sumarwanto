<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table): void {
            $table->id('id_pengaturan');
            $table->string('nama_pengaturan', 120)->unique();
            $table->text('nilai_pengaturan')->nullable();
            $table->foreignId('id_pengguna_pengubah')->nullable()->constrained('pengguna', 'id_pengguna')->nullOnDelete();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};
