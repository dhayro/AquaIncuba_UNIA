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
        Schema::create('lecturas_sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_sensor')->constrained('sensores')->onDelete('cascade');
            $table->foreignId('id_incubadora')->constrained('incubadoras')->onDelete('cascade');
            $table->foreignId('id_log_mqtt')->nullable()->constrained('logs_mqtt')->onDelete('set null');
            $table->float('valor_crudo');
            $table->float('valor_procesado');
            $table->string('unidad');
            $table->boolean('es_valido')->default(true);
            $table->boolean('esta_en_rango')->default(true);
            $table->string('bandera_calidad')->nullable();
            $table->timestamp('fecha_lectura');
            $table->timestamps();
            $table->index('id_sensor');
            $table->index('id_incubadora');
            $table->index('fecha_lectura');
            $table->index(['id_sensor', 'id_incubadora', 'fecha_lectura']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lecturas_sensores');
    }
};
