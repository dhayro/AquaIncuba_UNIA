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
        Schema::create('alertas_mqtt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sensor')->constrained('sensores')->onDelete('cascade');
            $table->foreignId('id_incubadora')->constrained('incubadoras')->onDelete('cascade');
            $table->foreignId('id_estudio_calidad')->nullable()->constrained('estudios_calidad_agua')->onDelete('set null');
            $table->string('nombre_sensor');
            $table->float('valor_actual');
            $table->float('valor_umbral');
            $table->string('tipo_alerta')->default('fuera_de_rango');
            $table->string('severidad')->default('media');
            $table->text('mensaje');
            $table->boolean('esta_resuelta')->default(false);
            $table->timestamp('fecha_resolucion')->nullable();
            $table->text('notas_resolucion')->nullable();
            $table->timestamps();
            $table->index('id_sensor');
            $table->index('id_incubadora');
            $table->index('created_at');
            $table->index('esta_resuelta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_mqtt');
    }
};
