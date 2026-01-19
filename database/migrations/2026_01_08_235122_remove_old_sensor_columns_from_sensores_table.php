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
        Schema::table('sensores', function (Blueprint $table) {
            // Primero eliminar las foreign keys
            if (Schema::hasColumn('sensores', 'id_tipo_sensor')) {
                $table->dropForeign(['id_tipo_sensor']);
            }
            if (Schema::hasColumn('sensores', 'id_unidad_medida')) {
                $table->dropForeign(['id_unidad_medida']);
            }
        });
        
        Schema::table('sensores', function (Blueprint $table) {
            // Ahora eliminar las columnas
            $columns = [
                'id_tipo_sensor',
                'id_unidad_medida',
                'minimo_optimo',
                'maximo_optimo',
                'minimo_critico',
                'maximo_critico',
                'decimales',
                'factor_calibracion',
                'notas_calibracion',
                'ultima_calibracion',
                'ultima_lectura',
                'ultimo_valor'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('sensores', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensores', function (Blueprint $table) {
            // Restaurar columnas antiguas en caso de rollback
            $table->foreignId('id_tipo_sensor')
                ->nullable()
                ->after('nombre')
                ->constrained('tipo_sensores')
                ->onDelete('set null');
            
            $table->foreignId('id_unidad_medida')
                ->nullable()
                ->after('id_tipo_sensor')
                ->constrained('unidades_medida')
                ->onDelete('set null');
            
            $table->decimal('minimo_optimo', 10, 4)->nullable();
            $table->decimal('maximo_optimo', 10, 4)->nullable();
            $table->decimal('minimo_critico', 10, 4)->nullable();
            $table->decimal('maximo_critico', 10, 4)->nullable();
            $table->integer('decimales')->default(2);
            $table->decimal('factor_calibracion', 10, 6)->default(1.0);
            $table->text('notas_calibracion')->nullable();
            $table->timestamp('ultima_calibracion')->nullable();
            $table->timestamp('ultima_lectura')->nullable();
            $table->decimal('ultimo_valor', 10, 4)->nullable();
        });
    }
};
