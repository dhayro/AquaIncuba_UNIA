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
        Schema::create('incubadoras_sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_incubadora')->constrained('incubadoras')->onDelete('cascade');
            $table->foreignId('id_sensor')->constrained('sensores')->onDelete('cascade');
            $table->integer('orden_posicion')->default(0);
            $table->boolean('esta_activo')->default(true);
            $table->text('notas_instalacion')->nullable();
            $table->timestamp('fecha_instalacion')->nullable();
            $table->timestamps();
            $table->unique(['id_incubadora', 'id_sensor']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incubadoras_sensores');
    }
};
