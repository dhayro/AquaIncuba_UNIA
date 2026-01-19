<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de agrupación: estudios_incubadoras (Many-to-Many)
     * Un estudio puede tener múltiples incubadoras
     * Una incubadora puede pertenecer a múltiples estudios
     */
    public function up(): void
    {
        // Primero, crear la tabla de relación M:M
        Schema::create('estudios_incubadoras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudio_calidad')->constrained('estudios_calidad_agua')->onDelete('cascade');
            $table->foreignId('id_incubadora')->constrained('incubadoras')->onDelete('cascade');
            $table->integer('orden_grupo')->default(0);
            $table->text('notas_incubadora')->nullable();
            $table->timestamp('fecha_agregada')->useCurrent();
            $table->timestamps();
            
            // Evitar duplicados
            $table->unique(['id_estudio_calidad', 'id_incubadora']);
            
            // Índices para búsquedas rápidas
            $table->index('id_estudio_calidad');
            $table->index('id_incubadora');
        });

        // Opcional: Migrar datos existentes de la relación anterior
        // Si hay incubadoras ya asignadas en estudios_calidad_agua, copiarlas a la nueva tabla
        DB::statement('
            INSERT IGNORE INTO estudios_incubadoras (id_estudio_calidad, id_incubadora, fecha_agregada, created_at, updated_at)
            SELECT id, id_incubadora, NOW(), NOW(), NOW()
            FROM estudios_calidad_agua
            WHERE id_incubadora IS NOT NULL
        ');

        // Finalmente, remover la columna id_incubadora de estudios_calidad_agua
        Schema::table('estudios_calidad_agua', function (Blueprint $table) {
            $table->dropForeign(['id_incubadora']);
            $table->dropColumn('id_incubadora');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar la columna id_incubadora
        Schema::table('estudios_calidad_agua', function (Blueprint $table) {
            $table->foreignId('id_incubadora')->nullable()->constrained('incubadoras')->onDelete('cascade');
        });

        // Migrar datos de vuelta
        DB::statement('
            UPDATE estudios_calidad_agua e
            SET e.id_incubadora = (
                SELECT id_incubadora FROM estudios_incubadoras
                WHERE id_estudio_calidad = e.id
                LIMIT 1
            )
        ');

        // Eliminar tabla de relación
        Schema::dropIfExists('estudios_incubadoras');
    }
};
