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
        Schema::create('configuraciones_mqtt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->unique()->constrained('empresas')->onDelete('cascade');
            $table->string('host_broker');
            $table->integer('puerto_broker')->default(1883);
            $table->string('usuario')->nullable();
            $table->string('contraseña')->nullable();
            $table->boolean('usar_tls')->default(false);
            $table->string('id_cliente');
            $table->integer('mantener_vivo')->default(60);
            $table->string('tema_base');
            $table->boolean('esta_activa')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones_mqtt');
    }
};
