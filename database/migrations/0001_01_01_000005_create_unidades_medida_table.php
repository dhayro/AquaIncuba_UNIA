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
        Schema::create('unidades_medida', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('simbolo')->unique();
            $table->text('descripcion')->nullable();
            $table->string('tipo')->nullable(); // ej: temperatura, presion, volumen, etc
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->index('nombre');
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_medida');
    }
};
