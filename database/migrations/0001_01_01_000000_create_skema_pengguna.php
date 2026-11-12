<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table): void {
            $table->id('id_pengguna');
            $table->string('nama_lengkap', 120);
            $table->string('alamat_email', 190)->unique();
            $table->string('kata_sandi');
            $table->boolean('status_aktif')->default(true)->index();
            $table->timestamp('terakhir_masuk_pada')->nullable();
            $table->string('token_ingatan', 100)->nullable();
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('peran', function (Blueprint $table): void {
            $table->id('id_peran');
            $table->string('nama_peran', 30)->unique();
            $table->string('keterangan_peran', 160);
            $table->timestamp('dibuat_pada')->nullable();
            $table->timestamp('diperbarui_pada')->nullable();
        });

        Schema::create('pengguna_peran', function (Blueprint $table): void {
            $table->foreignId('id_pengguna')->constrained('pengguna', 'id_pengguna')->cascadeOnDelete();
            $table->foreignId('id_peran')->constrained('peran', 'id_peran')->cascadeOnDelete();
            $table->primary(['id_pengguna', 'id_peran']);
        });

        // Tabel sesi memakai nama kolom bawaan Laravel karena database session handler
        // membaca kontrak skema tersebut secara langsung; ini tabel infrastruktur.
        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table): void {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('pengguna_peran');
        Schema::dropIfExists('peran');
        Schema::dropIfExists('pengguna');
    }
};
