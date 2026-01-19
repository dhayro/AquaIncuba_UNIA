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
        // 1. Crear tabla de asociación estudio_incubadora (M:M)
        Schema::create('estudio_incubadora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudio_id')->constrained('estudios_calidad_agua')->onDelete('cascade');
            $table->foreignId('incubadora_id')->constrained('incubadoras')->onDelete('cascade');
            $table->integer('orden_posicion')->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->unique(['estudio_id', 'incubadora_id']);
        });

        // 2. Migrar datos existentes si existen
        if (Schema::hasColumn('estudios_calidad_agua', 'id_incubadora')) {
            // Insertar datos existentes en la tabla de asociación
            DB::statement('INSERT INTO estudio_incubadora (estudio_id, incubadora_id, created_at, updated_at)
                          SELECT id, id_incubadora, NOW(), NOW() 
                          FROM estudios_calidad_agua 
                          WHERE id_incubadora IS NOT NULL');

            // 3. Eliminar la columna id_incubadora
            Schema::table('estudios_calidad_agua', function (Blueprint $table) {
                $table->dropForeign(['id_incubadora']);
                $table->dropColumn('id_incubadora');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar la columna id_incubadora
        if (!Schema::hasColumn('estudios_calidad_agua', 'id_incubadora')) {
            Schema::table('estudios_calidad_agua', function (Blueprint $table) {
                $table->unsignedBigInteger('id_incubadora')->nullable();
                $table->foreign('id_incubadora')->references('id')->on('incubadoras')->onDelete('cascade');
            });

            // Restaurar datos
            DB::statement('UPDATE estudios_calidad_agua e
                          SET id_incubadora = (
                            SELECT incubadora_id FROM estudio_incubadora WHERE estudio_id = e.id LIMIT 1
                          )');
        }

        // Eliminar tabla de asociación
        Schema::dropIfExists('estudio_incubadora');
    }
};
