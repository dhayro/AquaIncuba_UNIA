<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensor_parametro_mapping', function (Blueprint $table) {
            $table->id();
            $table->integer('sensor_id');
            $table->string('sensor_nombre');
            $table->integer('id_estudio');
            $table->integer('id_parametro');
            $table->string('tipo_extraccion')->default('directo');
            $table->string('clave_json')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
            
            // Un sensor puede mapear a múltiples parámetros (ej: sensor con pH+Temp mapea a 2 params)
            $table->unique(['sensor_id', 'id_estudio', 'id_parametro', 'clave_json'], 'spm_unique_mapping');
            $table->index('id_estudio');
            $table->index('sensor_id');
            $table->index('id_parametro');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensor_parametro_mapping');
    }
};
