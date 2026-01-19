<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ciclos_procesados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciclos_mqtt_crudo_id');
            $table->integer('ciclo_numero');
            $table->integer('id_estudio');
            $table->integer('sensor_count');
            $table->integer('lecturas_insertadas');
            $table->enum('estado', ['EXITOSO', 'PARCIAL', 'ERROR'])->default('EXITOSO');
            $table->text('detalles_json')->nullable();
            $table->timestamp('fecha_procesado')->useCurrent();
            $table->timestamps();
            
            $table->foreign('ciclos_mqtt_crudo_id')
                  ->references('id')
                  ->on('ciclos_mqtt_crudos')
                  ->onDelete('cascade');
            $table->index('ciclo_numero');
            $table->index('id_estudio');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ciclos_procesados');
    }
};
