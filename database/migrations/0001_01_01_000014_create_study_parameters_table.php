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
        Schema::create('parametros_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('unidad');
            $table->string('tipo_medicion')->default('ambas');
            $table->float('minimo_optimo')->nullable();
            $table->float('maximo_optimo')->nullable();
            $table->float('minimo_critico')->nullable();
            $table->float('maximo_critico')->nullable();
            $table->integer('decimales')->default(2);
            $table->timestamps();
            $table->unique(['id_empresa', 'codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_estudio');
    }
};
