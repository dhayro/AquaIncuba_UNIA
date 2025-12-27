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
        Schema::create('logs_mqtt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dispositivo_mqtt')->constrained('dispositivos_mqtt')->onDelete('cascade');
            $table->foreignId('id_tema_mqtt')->constrained('temas_mqtt')->onDelete('cascade');
            $table->string('tema');
            $table->text('contenido');
            $table->float('valor')->nullable();
            $table->string('unidad')->nullable();
            $table->boolean('es_valido')->default(true);
            $table->timestamp('fecha_grabacion');
            $table->timestamps();
            $table->index('id_dispositivo_mqtt');
            $table->index('fecha_grabacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs_mqtt');
    }
};
