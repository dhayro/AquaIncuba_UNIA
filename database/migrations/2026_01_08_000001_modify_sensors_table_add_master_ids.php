<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Modify sensores table to use master tables
     */
    public function up(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            // Agregar columnas para foreign keys
            $table->foreignId('id_tipo_sensor')
                ->nullable()
                ->after('nombre')
                ->constrained('tipo_sensores')
                ->onDelete('set null');
            
            $table->foreignId('id_unidad_medida')
                ->nullable()
                ->after('id_tipo_sensor')
                ->constrained('unidades_medida')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            // Eliminar las foreign keys
            $table->dropForeignIdFor('tipo_sensores');
            $table->dropForeignIdFor('unidades_medida');
            
            // Eliminar las columnas
            $table->dropColumn('id_tipo_sensor');
            $table->dropColumn('id_unidad_medida');
        });
    }
};
