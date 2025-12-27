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
        Schema::create('temas_mqtt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('tema');
            $table->string('descripcion')->nullable();
            $table->string('tipo_dato');
            $table->string('unidad')->nullable();
            $table->float('valor_minimo')->nullable();
            $table->float('valor_maximo')->nullable();
            $table->boolean('esta_activo')->default(true);
            $table->timestamps();
            $table->unique(['id_empresa', 'tema']);
            $table->index('id_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temas_mqtt');
    }
};
