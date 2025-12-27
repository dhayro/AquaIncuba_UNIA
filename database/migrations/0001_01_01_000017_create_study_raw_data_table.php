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
        Schema::create('datos_crudos_estudio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_muestra_estudio')->constrained('muestras_estudio')->onDelete('cascade');
            $table->foreignId('id_sensor')->constrained('sensores')->onDelete('cascade');
            $table->foreignId('id_lectura_sensor')->nullable()->constrained('lecturas_sensores')->onDelete('set null');
            $table->text('contenido_crudo');
            $table->float('valor_crudo')->nullable();
            $table->string('unidad_cruda')->nullable();
            $table->timestamp('fecha_recepcion');
            $table->timestamps();
            $table->index('id_muestra_estudio');
            $table->index('id_sensor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_crudos_estudio');
    }
};
