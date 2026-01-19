<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciclos_mqtt_crudos', function (Blueprint $table) {
            $table->id();
            $table->integer('ciclo_numero'); // SIN unique() - permite ciclos duplicados
            $table->longText('payload_json');
            $table->enum('estado', ['PENDIENTE', 'PROCESADO', 'ERROR'])->default('PENDIENTE');
            $table->timestamp('fecha_recibido')->useCurrent();
            $table->timestamp('fecha_procesado')->nullable();
            $table->text('error_mensaje')->nullable();
            $table->timestamps();
            
            $table->index('estado');
            $table->index('ciclo_numero');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciclos_mqtt_crudos');
    }
};
