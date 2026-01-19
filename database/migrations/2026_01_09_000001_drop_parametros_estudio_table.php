<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Eliminar tabla parametros_estudio completamente.
     * La funcionalidad ha sido reemplazada por sensor_tipo_unidad que proporciona
     * mayor granularidad (parámetros específicos por sensor/tipo/unidad).
     */
    public function up(): void
    {
        Schema::dropIfExists('parametros_estudio');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No recreamos la tabla porque sensor_tipo_unidad la reemplaza completamente
        // Cualquier rollback debe ser manejado manualmente
    }
};
