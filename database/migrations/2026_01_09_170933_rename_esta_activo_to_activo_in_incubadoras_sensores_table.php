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
        // Esta migración ya se realizó - saltarla
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incubadoras_sensores', function (Blueprint $table) {
            $table->dropColumn('activo');
        });

        Schema::table('incubadoras_sensores', function (Blueprint $table) {
            $table->boolean('esta_activo')->default(true)->after('orden_posicion');
        });
    }
};
