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
        Schema::create('sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->string('tipo_sensor');
            $table->text('descripcion')->nullable();
            $table->foreignId('id_dispositivo_mqtt')->nullable()->constrained('dispositivos_mqtt')->onDelete('set null');
            $table->string('tema_mqtt');
            $table->string('unidad');
            $table->float('minimo_optimo')->nullable();
            $table->float('maximo_optimo')->nullable();
            $table->float('minimo_critico')->nullable();
            $table->float('maximo_critico')->nullable();
            $table->integer('decimales')->default(2);
            $table->float('factor_calibracion')->default(1.0);
            $table->text('notas_calibracion')->nullable();
            $table->timestamp('ultima_calibracion')->nullable();
            $table->string('estado')->default('activo');
            $table->timestamp('ultima_lectura')->nullable();
            $table->float('ultimo_valor')->nullable();
            $table->timestamps();
            $table->unique(['id_empresa', 'codigo']);
            $table->index('id_empresa');
            $table->index('tema_mqtt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensores');
    }
};
