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
        Schema::create('dispositivos_mqtt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('id_dispositivo')->unique();
            $table->string('tipo_dispositivo');
            $table->string('tema_mqtt');
            $table->string('ubicacion')->nullable();
            $table->boolean('esta_activo')->default(true);
            $table->timestamp('ultima_lectura')->nullable();
            $table->string('version_firmware')->nullable();
            $table->timestamps();
            $table->unique(['id_empresa', 'id_dispositivo']);
            $table->index('id_empresa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispositivos_mqtt');
    }
};
