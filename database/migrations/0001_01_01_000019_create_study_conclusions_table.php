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
        Schema::create('conclusiones_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudio_calidad')->constrained('estudios_calidad_agua')->onDelete('cascade');
            $table->text('resumen');
            $table->string('estado_general')->default('bueno');
            $table->float('porcentaje_calidad')->nullable();
            $table->text('hallazgos')->nullable();
            $table->text('anomalias')->nullable();
            $table->text('recomendaciones')->nullable();
            $table->text('acciones_requeridas')->nullable();
            $table->string('urgencia_acciones')->nullable();
            $table->foreignId('id_concluido_por')->constrained('usuarios')->onDelete('cascade');
            $table->timestamp('fecha_conclusion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conclusiones_estudio');
    }
};
