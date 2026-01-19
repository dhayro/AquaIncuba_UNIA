<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\TipoSensor;
use App\Models\UnidadMedida;
use App\Models\SensorTipoUnidad;

class SensorTipoUnidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener datos
        $sensores = Sensor::all();
        $tiposSensor = TipoSensor::activo()->get();
        $unidadesMedida = UnidadMedida::activo()->get();

        // Crear ejemplos de asignaciones basadas en tipos de sensor
        // Nota: En un sistema real, esto vendría de una configuración más compleja

        foreach ($sensores as $sensor) {
            // Cada sensor tendrá al menos un tipo-unidad asignado
            // Algunos sensores tendrán múltiples tipos (para sensores multi-parámetro)

            // Asignar tipo de sensor al azar o de forma organizada
            $tiposAsignados = [];
            
            // Para varietad: algunos sensores miden 1-2 tipos, otros hasta 3
            $cantidadTipos = rand(1, min(3, $tiposSensor->count()));
            $tiposSeleccionados = $tiposSensor->random($cantidadTipos);

            foreach ($tiposSeleccionados as $tipo) {
                // Seleccionar una o dos unidades compatibles para cada tipo
                $cantidadUnidades = rand(1, 2);
                $unidadesSeleccionadas = $unidadesMedida->random($cantidadUnidades);

                foreach ($unidadesSeleccionadas as $unidad) {
                    // Evitar duplicados
                    $key = "{$sensor->id}-{$tipo->id}-{$unidad->id}";
                    if (in_array($key, $tiposAsignados)) {
                        continue;
                    }
                    $tiposAsignados[] = $key;

                    // Crear la asignación con valores por defecto según el tipo
                    SensorTipoUnidad::create([
                        'sensor_id' => $sensor->id,
                        'tipo_sensor_id' => $tipo->id,
                        'unidad_medida_id' => $unidad->id,
                        'minimo_optimo' => $this->getValorOptimo($tipo->nombre, 'min'),
                        'maximo_optimo' => $this->getValorOptimo($tipo->nombre, 'max'),
                        'minimo_critico' => $this->getValorCritico($tipo->nombre, 'min'),
                        'maximo_critico' => $this->getValorCritico($tipo->nombre, 'max'),
                        'decimales' => 2,
                        'factor_calibracion' => 1.0,
                        'activo' => true,
                    ]);
                }
            }
        }

        $this->command->info('✅ Asignaciones de sensor-tipo-unidad creadas exitosamente');
    }

    /**
     * Obtener valores óptimos según el tipo de sensor
     */
    private function getValorOptimo($tipoNombre, $tipo): float
    {
        $valores = [
            'Temperatura' => ['min' => 20.0, 'max' => 30.0],
            'pH' => ['min' => 6.5, 'max' => 7.5],
            'Oxígeno Disuelto' => ['min' => 6.0, 'max' => 8.0],
            'Conductividad' => ['min' => 1.0, 'max' => 2.0],
            'Turbidez' => ['min' => 0.0, 'max' => 5.0],
            'Salinidad' => ['min' => 0.0, 'max' => 35.0],
        ];

        return $valores[$tipoNombre][$tipo] ?? ($tipo === 'min' ? 0 : 100);
    }

    /**
     * Obtener valores críticos según el tipo de sensor
     */
    private function getValorCritico($tipoNombre, $tipo): float
    {
        $valores = [
            'Temperatura' => ['min' => 5.0, 'max' => 40.0],
            'pH' => ['min' => 5.0, 'max' => 9.0],
            'Oxígeno Disuelto' => ['min' => 2.0, 'max' => 15.0],
            'Conductividad' => ['min' => 0.5, 'max' => 5.0],
            'Turbidez' => ['min' => 0.0, 'max' => 20.0],
            'Salinidad' => ['min' => 0.0, 'max' => 40.0],
        ];

        return $valores[$tipoNombre][$tipo] ?? ($tipo === 'min' ? -50 : 150);
    }
}
