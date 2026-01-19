<?php

namespace App\Services;

use App\Models\CicloMqttCrudo;
use App\Models\SensorParametroMapping;
use App\Models\EstudioCalidadAgua;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcesarCiclosService
{
    public function procesarCiclosPendientes()
    {
        $ciclos = CicloMqttCrudo::where('estado', 'PENDIENTE')
                               ->orderBy('id')
                               ->limit(10)
                               ->get();

        foreach ($ciclos as $ciclo) {
            $this->procesarCiclo($ciclo);
        }

        return count($ciclos);
    }

    private function procesarCiclo($ciclo)
    {
        try {
            $payload = $ciclo->payload_json;
            
            if (!isset($payload['sensores'])) {
                throw new \Exception('Formato inválido: no contiene sensores');
            }

            // Buscar estudios activos (en_progreso)
            $estudios = EstudioCalidadAgua::where('estado', 'en_progreso')
                                         ->get();

            if ($estudios->isEmpty()) {
                throw new \Exception('No hay estudios activos');
            }

            $lecturas_insertadas = 0;

            foreach ($estudios as $estudio) {
                // Obtener mappings para este estudio
                $mappings = SensorParametroMapping::getMappingsForStudy($estudio->id);

                if ($mappings->isEmpty()) {
                    Log::warning("No hay mappings para estudio {$estudio->id}");
                    continue;
                }

                // Procesar cada sensor del ciclo
                foreach ($payload['sensores'] as $sensor) {
                    // Obtener TODOS los mappings para este sensor (puede tener múltiples parámetros)
                    $sensorMappings = $mappings->where('sensor_id', $sensor['sensor_id']);

                    if ($sensorMappings->isEmpty()) {
                        continue;
                    }

                    // Procesar cada mapping (parámetro) del sensor
                    foreach ($sensorMappings as $mapping) {
                        // Extraer valor según tipo (puede ser null si hay datos inválidos)
                        $valor = $this->extraerValor($sensor, $mapping);

                        // Guardar SIEMPRE, incluso si el valor es NULL
                        // (NULL indica un sensor con datos inválidos que fueron sanitizados)
                        DB::table('mqtt_lecturas')->insert([
                            'ciclos_mqtt_crudo_id' => $ciclo->id,
                            'id_estudio' => $estudio->id,
                            'id_parametro' => $mapping->id_parametro,
                            'valor' => $valor, // Puede ser NULL
                            'origen' => 'PLC',
                            'ciclo_numero' => $payload['ciclo_numero'],
                            'fechaRegistro' => Carbon::now(),
                            'estado' => 'ACTIVO',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $lecturas_insertadas++;
                    }
                }
            }

            // Marcar ciclo como procesado
            $ciclo->update([
                'estado' => 'PROCESADO',
                'fecha_procesado' => Carbon::now()
            ]);

            Log::info("Ciclo {$ciclo->ciclo_numero} procesado. {$lecturas_insertadas} lecturas insertadas");

        } catch (\Exception $e) {
            $ciclo->update([
                'estado' => 'ERROR',
                'error_mensaje' => $e->getMessage()
            ]);

            Log::error("Error procesando ciclo {$ciclo->id}: {$e->getMessage()}");
        }
    }

    private function extraerValor($sensor, $mapping)
    {
        if ($mapping->tipo_extraccion === 'objeto') {
            // Es un objeto, necesitamos extraer la clave
            if (is_array($sensor['valor'])) {
                return $sensor['valor'][$mapping->clave_json] ?? null;
            }
            return null;
        }

        // Es valor directo
        return $sensor['valor'];
    }
}
