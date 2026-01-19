<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Eliminar la tabla estudios_incubadoras que fue reemplazada por estudio_incubadora
     */
    public function up(): void
    {
        Schema::dropIfExists('estudios_incubadoras');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No se restaura - tabla obsoleta
    }
};
