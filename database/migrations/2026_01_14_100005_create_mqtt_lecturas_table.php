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
        Schema::create('mqtt_lecturas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ciclos_mqtt_crudo_id')->index();
            $table->unsignedBigInteger('id_estudio');
            $table->unsignedBigInteger('id_parametro');
            $table->decimal('valor', 15, 4)->nullable(); // Permitir NULL para sensores con datos inválidos
            $table->string('origen')->default('PLC'); // PLC, MANUAL, API
            $table->unsignedInteger('ciclo_numero');
            $table->timestamp('fechaRegistro')->useCurrent();
            $table->string('estado')->default('ACTIVO');
            $table->timestamps();

            // Foreign keys
            $table->foreign('ciclos_mqtt_crudo_id')
                ->references('id')
                ->on('ciclos_mqtt_crudos')
                ->onDelete('cascade');

            // Indexes
            $table->index(['id_estudio', 'id_parametro']);
            $table->index('ciclo_numero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mqtt_lecturas');
    }
};
