<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Autentikasi menggunakan skema `pengguna` pada migration aplikasi.
    }

    public function down(): void
    {
        // Migration skema aplikasi mengelola tabel autentikasi dan sesi.
    }
};
