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
        // No hacer cambios - la tabla ya está correcta
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar el unique constraint
        DB::statement('ALTER TABLE ciclos_mqtt_crudos ADD UNIQUE ciclos_mqtt_crudos_ciclo_numero_unique(ciclo_numero)');
    }
};

