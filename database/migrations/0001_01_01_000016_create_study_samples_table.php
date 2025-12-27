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
        Schema::create('muestras_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudio_calidad')->constrained('estudios_calidad_agua')->onDelete('cascade');
            $table->string('codigo_muestra')->unique();
            $table->dateTime('fecha_hora_muestra');
            $table->integer('numero_secuencia');
            $table->text('observacion')->nullable();
            $table->boolean('es_valida')->default(true);
            $table->timestamps();
            $table->index('id_estudio_calidad');
            $table->index('fecha_hora_muestra');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('muestras_estudio');
    }
};
