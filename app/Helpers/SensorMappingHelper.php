<?php

namespace App\Helpers;

use App\Models\SensorParametroMapping;
use App\Models\Sensor;
use App\Models\SensorTipoUnidad;

class SensorMappingHelper
{
    /**
     * Generar automáticamente SensorParametroMapping para un estudio
     * basado en sus incubadoras y sensores asignados
     */
    public static function generarMappingsParaEstudio($id_estudio)
    {
        // Obtener estudio con incubadoras
        $estudio = \App\Models\EstudioCalidadAgua::find($id_estudio);
        if (!$estudio) {
            throw new \Exception("Estudio no encontrado");
        }

        $sensores_creados = 0;
        
        // Obtener todos los sensores de todas las incubadoras del estudio
        foreach ($estudio->incubadoras as $incubadora) {
            foreach ($incubadora->sensores as $sensor) {
                // Obtener todos los SensorTipoUnidad para este sensor
                $sensorTipoUnidades = SensorTipoUnidad::where('sensor_id', $sensor->id)->get();
                
                foreach ($sensorTipoUnidades as $stu) {
                    // Verificar si ya existe el mapping
                    $existe = SensorParametroMapping::where('id_estudio', $id_estudio)
                                                    ->where('sensor_id', $sensor->id)
                                                    ->where('id_parametro', $stu->id)
                                                    ->exists();
                    
                    if ($existe) {
                        continue;
                    }

                    // Determinar tipo de extracción y clave JSON
                    $tipo_extraccion = self::determinarTipoExtraccion($sensor->id);
                    $clave_json = self::determinarClaveJson($sensor->id, $stu->id);

                    // Crear mapping
                    SensorParametroMapping::create([
                        'sensor_id' => $sensor->id,
                        'sensor_nombre' => $sensor->nombre,
                        'id_estudio' => $id_estudio,
                        'id_parametro' => $stu->id,
                        'tipo_extraccion' => $tipo_extraccion,
                        'clave_json' => $clave_json,
                        'activo' => true,
                        'notas' => self::generarNotas($sensor->id, $stu->id)
                    ]);

                    $sensores_creados++;
                }
            }
        }

        return $sensores_creados;
    }

    /**
     * Determinar si el sensor tiene valor simple o complejo (objeto)
     */
    private static function determinarTipoExtraccion($sensor_id)
    {
        // Sensores con valores complejos (objeto JSON)
        $sensores_complejos = [1, 4, 5, 6, 7, 10, 11, 12]; // pH+Temp, Nitrato+Temp, etc.
        
        if (in_array($sensor_id, $sensores_complejos)) {
            return 'objeto';
        }
        
        return 'directo';
    }

    /**
     * Determinar la clave JSON para extraer el valor
     */
    private static function determinarClaveJson($sensor_id, $stu_id)
    {
        // Mapeo: sensor_id => [stu_id => clave_json]
        $mapeos = [
            1 => [1 => 'pH', 2 => 'temperatura'],           // Sensor pH + Temperatura
            3 => [4 => 'valor'],                             // Sensor Oxígeno Disuelto
            4 => [5 => 'nitrato', 6 => 'temperatura'],       // Sensor Nitrato + Temperatura
            5 => [7 => 'amonio', 8 => 'temperatura'],        // Sensor Amonio + Temperatura
            6 => [9 => 'turbidez', 10 => 'temperatura'],     // Sensor Turbidez + Temperatura
            7 => [11 => 'pH', 12 => 'temperatura'],          // Sensor pH + Temperatura
            8 => [13 => 'valor'],                            // Sensor Conductividad
            9 => [14 => 'valor'],                            // Sensor Oxígeno Disuelto
            10 => [15 => 'nitrato', 16 => 'temperatura'],    // Sensor Nitrato + Temperatura
            11 => [17 => 'amonio', 18 => 'temperatura'],     // Sensor Amonio + Temperatura
            12 => [19 => 'turbidez', 20 => 'temperatura'],   // Sensor Turbidez + Temperatura
        ];

        if (isset($mapeos[$sensor_id][$stu_id])) {
            return $mapeos[$sensor_id][$stu_id];
        }

        // Por defecto, usar 'valor'
        return 'valor';
    }

    /**
     * Generar notas descriptivas
     */
    private static function generarNotas($sensor_id, $stu_id)
    {
        $sensor = Sensor::find($sensor_id);
        $stu = SensorTipoUnidad::find($stu_id);
        $tipo = \App\Models\TipoSensor::find($stu->tipo_sensor_id);
        
        return "Sensor: {$sensor->nombre}, Tipo: {$tipo->nombre}";
    }
}
