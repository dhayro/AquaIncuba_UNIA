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
        Schema::table('ciclos_mqtt_crudos', function (Blueprint $table) {
            // Quitar el unique constraint si existe
            $table->dropUnique(['ciclo_numero']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ciclos_mqtt_crudos', function (Blueprint $table) {
            // Restaurar el unique constraint
            $table->unique(['ciclo_numero']);
        });
    }
};
