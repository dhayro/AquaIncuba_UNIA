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
        Schema::create('estudios_calidad_agua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('id_incubadora')->constrained('incubadoras')->onDelete('cascade');
            $table->string('codigo_estudio')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->string('estado')->default('planificado');
            $table->text('notas')->nullable();
            $table->foreignId('id_creado_por')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
            $table->index('id_empresa');
            $table->index('id_incubadora');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudios_calidad_agua');
    }
};
