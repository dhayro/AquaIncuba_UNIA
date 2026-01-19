<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Esta migración causa conflicto - la columna ya está correcta
        // No hacer cambios
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacer rollback de este cambio - es mejor mantener como boolean
        // Schema::table('tipo_sensores', function (Blueprint $table) {
        //     $table->string('activo')->default('activo')->change();
        // });
    }
};
