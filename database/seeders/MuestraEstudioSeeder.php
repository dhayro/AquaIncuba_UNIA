<?php

namespace Database\Seeders;

use App\Models\EstudioCalidadAgua;
use App\Models\MuestraEstudio;
use Illuminate\Database\Seeder;

class MuestraEstudioSeeder extends Seeder
{
    /**
     * Crear muestras de estudio para pruebas MQTT
     * 
     * Ejecutar con:
     * php artisan db:seed --class=MuestraEstudioSeeder
     * 
     * O agregar a DatabaseSeeder y ejecutar:
     * php artisan migrate:refresh --seed
     */
    public function run(): void
    {
        // Obtener todos los estudios
        $estudios = EstudioCalidadAgua::all();

        if ($estudios->isEmpty()) {
            $this->command->warn('⚠️ No hay estudios registrados. Ejecuta EstudioCalidadAguaSeeder primero.');
            return;
        }

        $totalMuestras = 0;
        $year = date('Y');

        foreach ($estudios as $estudio) {
            // Crear 3 muestras por estudio
            for ($numeroMuestra = 1; $numeroMuestra <= 3; $numeroMuestra++) {
                // Generar código único de muestra
                $codigoMuestra = $estudio->codigo_estudio . '-M' . str_pad($numeroMuestra, 3, '0', STR_PAD_LEFT);

                MuestraEstudio::create([
                    'id_estudio_calidad' => $estudio->id,
                    'codigo_muestra' => $codigoMuestra,
                    'fecha_hora_muestra' => now()
                        ->subDays(4 - $numeroMuestra)  // 3 días atrás, 2 días, 1 día
                        ->setTime(9 + ($numeroMuestra * 2), 0, 0),
                    'numero_secuencia' => $numeroMuestra,
                    'observacion' => "Muestra de prueba para MQTT - Estudio: {$estudio->nombre}",
                    'es_valida' => true,
                ]);

                $totalMuestras++;
            }

            $this->command->info(
                "✅ Estudio '{$estudio->nombre}': 3 muestras creadas"
            );
        }

        $this->command->info("🎉 Total de muestras creadas: {$totalMuestras}");
    }
}
