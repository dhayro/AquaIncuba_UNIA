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
        Schema::create('sensor_tipo_unidad', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('sensor_id')
                ->constrained('sensores')
                ->onDelete('cascade');
            
            $table->foreignId('tipo_sensor_id')
                ->constrained('tipo_sensores')
                ->onDelete('cascade');
            
            $table->foreignId('unidad_medida_id')
                ->constrained('unidades_medida')
                ->onDelete('restrict');
            
            // Campos opcionales para caracterización de la medición
            $table->decimal('minimo_optimo', 10, 4)->nullable()->comment('Rango mínimo óptimo para este tipo en este sensor');
            $table->decimal('maximo_optimo', 10, 4)->nullable()->comment('Rango máximo óptimo para este tipo en este sensor');
            $table->decimal('minimo_critico', 10, 4)->nullable()->comment('Umbral mínimo crítico para alerta');
            $table->decimal('maximo_critico', 10, 4)->nullable()->comment('Umbral máximo crítico para alerta');
            $table->integer('decimales')->default(2)->comment('Decimales para esta medición');
            $table->decimal('factor_calibracion', 10, 6)->default(1.0)->comment('Factor de calibración');
            
            // Control
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices para búsquedas frecuentes
            $table->unique(['sensor_id', 'tipo_sensor_id', 'unidad_medida_id'], 'unique_sensor_tipo_unidad');
            $table->index('tipo_sensor_id');
            $table->index('unidad_medida_id');
            $table->index('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_tipo_unidad');
    }
};
