<?php

namespace Database\Seeders;

use App\Models\Sensor;
use App\Models\LecturaSensor;
use Illuminate\Database\Seeder;

class LecturaSensorSeeder extends Seeder
{
    /**
     * Crear lecturas de sensores para pruebas MQTT
     * 
     * Ejecutar con:
     * php artisan db:seed --class=LecturaSensorSeeder
     * 
     * O agregar a DatabaseSeeder y ejecutar:
     * php artisan migrate:refresh --seed
     * 
     * NOTA: Primero debe existir MuestraEstudio y Sensor
     */
    public function run(): void
    {
        // Obtener todas las incubadoras con sus sensores
        $incubadorasSensores = \DB::table('incubadoras_sensores')->get();

        if ($incubadorasSensores->isEmpty()) {
            $this->command->warn('⚠️ No hay relaciones incubadora-sensor registradas.');
            return;
        }

        $totalLecturas = 0;

        // Crear 3 lecturas por relación incubadora-sensor
        foreach ($incubadorasSensores as $incSensor) {
            $sensor = Sensor::find($incSensor->id_sensor);
            if (!$sensor) continue;

            for ($i = 1; $i <= 3; $i++) {
                // Generar valor realista según el tipo de sensor
                $valor = $this->generarValorPorTipo($sensor);

                LecturaSensor::create([
                    'id_sensor' => $sensor->id,
                    'id_incubadora' => $incSensor->id_incubadora,
                    'id_log_mqtt' => null,  // Sin asociar a log por ahora
                    'valor_crudo' => $valor,
                    'valor_procesado' => $valor,  // En esta prueba es igual
                    'unidad' => $this->obtenerUnidadPorTipo($sensor),
                    'es_valido' => true,
                    'esta_en_rango' => $this->estaEnRango($sensor, $valor),
                    'bandera_calidad' => 'OK',
                    'fecha_lectura' => now()->subDays(4 - $i),
                ]);

                $totalLecturas++;
            }
        }

        $this->command->info("✅ {$totalLecturas} lecturas de sensores creadas");
    }

    /**
     * Generar valor realista según el tipo de sensor
     */
    private function generarValorPorTipo(Sensor $sensor): float
    {
        // Obtener el tipo del sensor desde SensorTipoUnidad
        $sensorTipoUnidad = $sensor->sensorTipoUnidades()->first();

        if (!$sensorTipoUnidad) {
            // Si no tiene tipo asignado, generar valor aleatorio
            return rand(100, 500) / 10;
        }

        $tipo = $sensorTipoUnidad->tipoSensor->nombre;

        // Valores realistas según tipo de sensor
        $valores = [
            'Temperatura' => [
                'min' => 15,
                'max' => 35,
                'optimo_min' => 20,
                'optimo_max' => 30,
            ],
            'pH' => [
                'min' => 5,
                'max' => 9,
                'optimo_min' => 6.5,
                'optimo_max' => 7.5,
            ],
            'Oxígeno Disuelto' => [
                'min' => 2,
                'max' => 12,
                'optimo_min' => 6,
                'optimo_max' => 8,
            ],
            'Conductividad' => [
                'min' => 0,
                'max' => 5,
                'optimo_min' => 1,
                'optimo_max' => 2.5,
            ],
            'Turbidez' => [
                'min' => 0,
                'max' => 20,
                'optimo_min' => 0,
                'optimo_max' => 5,
            ],
            'Salinidad' => [
                'min' => 0,
                'max' => 40,
                'optimo_min' => 0,
                'optimo_max' => 35,
            ],
        ];

        $config = $valores[$tipo] ?? [
            'min' => 0,
            'max' => 100,
            'optimo_min' => 20,
            'optimo_max' => 80,
        ];

        // 70% de probabilidad de valor óptimo, 30% aleatorio
        if (rand(1, 100) <= 70) {
            // Valor óptimo
            $min = $config['optimo_min'] * 100;
            $max = $config['optimo_max'] * 100;
        } else {
            // Valor aleatorio (incluyendo fuera de rango)
            $min = $config['min'] * 100;
            $max = $config['max'] * 100;
        }

        return rand($min, $max) / 100;
    }

    /**
     * Obtener unidad de medida según el tipo de sensor
     */
    private function obtenerUnidadPorTipo(Sensor $sensor): string
    {
        $sensorTipoUnidad = $sensor->sensorTipoUnidades()->first();

        if (!$sensorTipoUnidad) {
            return 'N/A';
        }

        return $sensorTipoUnidad->unidadMedida->simbolo ?? 'N/A';
    }

    /**
     * Verificar si el valor está en rango óptimo
     */
    private function estaEnRango(Sensor $sensor, float $valor): bool
    {
        $sensorTipoUnidad = $sensor->sensorTipoUnidades()->first();

        if (!$sensorTipoUnidad) {
            return true;
        }

        return $valor >= $sensorTipoUnidad->minimo_optimo &&
               $valor <= $sensorTipoUnidad->maximo_optimo;
    }
}
