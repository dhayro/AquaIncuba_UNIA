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
        Schema::create('datos_procesados_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_muestra_estudio')->constrained('muestras_estudio')->onDelete('cascade');
            $table->foreignId('id_sensor')->constrained('sensores')->onDelete('cascade');
            $table->float('valor_procesado');
            $table->string('unidad');
            $table->float('promedio')->nullable();
            $table->float('minimo_lectura')->nullable();
            $table->float('maximo_lectura')->nullable();
            $table->float('desv_estandar')->nullable();
            $table->integer('cantidad_lecturas')->nullable();
            $table->string('estado')->default('valido');
            $table->text('bandera_calidad')->nullable();
            $table->string('interpretacion')->nullable();
            $table->text('notas')->nullable();
            $table->foreignId('id_revisado_por')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();
            $table->index('id_muestra_estudio');
            $table->index('id_sensor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_procesados_estudio');
    }
};
