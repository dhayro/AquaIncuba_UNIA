<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Saltarse esta migración - la columna ya está correcta
        // renameColumn tiene issues con MariaDB
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer nada
    }
};
