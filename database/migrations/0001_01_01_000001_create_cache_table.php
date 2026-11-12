<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Infrastructure tables are defined alongside the localized application schema.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Infrastructure tables are dropped by their owning schema migration.
    }
};
