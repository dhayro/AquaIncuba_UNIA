<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EstudioCalidadAgua;
use App\Models\MqttLectura;
use App\Models\SensorParametroMapping;
use Illuminate\Http\Request;

class EstudioDatosApiController extends Controller
{
    /**
     * Obtener datos históricos de un sensor específico en una incubadora
     * GET /api/estudios/{estudio}/sensor-datos/{incubadora}/{sensorNombre}/{parametro?}
     */
    public function obtenerDatosSensor($estudioId, $incubadoraId, $sensorNombre, $parametro = null)
    {
        try {
            $estudio = EstudioCalidadAgua::findOrFail($estudioId);

            // Decodificar el nombre del sensor si fue encoded
            // Usar rawurldecode() para preservar correctamente el signo +
            $sensorNombre = rawurldecode($sensorNombre);
            if ($parametro) {
                $parametro = rawurldecode($parametro);
            }
            
            // Verificar que la incubadora existe en el estudio
            $incubadora = $estudio->incubadoras()->find($incubadoraId);
            if (!$incubadora) {
                return response()->json([
                    'success' => false,
                    'message' => 'Incubadora no encontrada en este estudio',
                    'lecturas' => []
                ], 404);
            }
            
            // Obtener el sensor por nombre
            $sensor = $incubadora->sensores()->where('nombre', $sensorNombre)->first();
            if (!$sensor) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sensor no encontrado en esta incubadora',
                    'sensor_buscado' => $sensorNombre,
                    'sensores_disponibles' => $incubadora->sensores->pluck('nombre')->toArray(),
                    'lecturas' => []
                ], 404);
            }
            
            // Obtener los mappings para este sensor en este estudio
            $query = SensorParametroMapping::where('id_estudio', $estudioId)
                ->where('sensor_id', $sensor->id);
            
            // Si se especifica un parámetro, filtrar por él
            if ($parametro) {
                $query = $query->with('parametro')
                    ->get()
                    ->filter(function($mapping) use ($parametro) {
                        // Obtener nombre del tipo de sensor del parámetro
                        $tipoSensor = $mapping->parametro->tipoSensor->nombre ?? '';
                        return stripos($tipoSensor, $parametro) !== false;
                    });
                $mappings = $query->pluck('id_parametro')->toArray();
            } else {
                $mappings = $query->pluck('id_parametro')->toArray();
            }

            if (empty($mappings)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontraron mappings para este sensor en este estudio',
                    'parametro_buscado' => $parametro,
                    'lecturas' => []
                ], 404);
            }

            // Obtener todas las lecturas ordenadas por fecha
            $lecturas = MqttLectura::where('id_estudio', $estudioId)
                ->whereIn('id_parametro', $mappings)
                ->orderBy('created_at', 'asc')
                ->select('id', 'id_parametro', 'valor', 'created_at')
                ->get();
            
            // Obtener el nombre del parámetro/tipo sensor para mostrar en respuesta
            $nombreParametro = '';
            if ($parametro) {
                $nombreParametro = $parametro;
            } else if (!empty($mappings)) {
                // Obtener el primer parámetro disponible
                $primerMapping = SensorParametroMapping::where('id_estudio', $estudioId)
                    ->where('sensor_id', $sensor->id)
                    ->with('parametro.tipoSensor')
                    ->first();
                if ($primerMapping && $primerMapping->parametro && $primerMapping->parametro->tipoSensor) {
                    $nombreParametro = $primerMapping->parametro->tipoSensor->nombre;
                }
            }

            return response()->json([
                'success' => true,
                'sensor' => $sensorNombre,
                'parametro' => $nombreParametro,
                'incubadora_id' => $incubadoraId,
                'total_lecturas' => $lecturas->count(),
                'lecturas' => $lecturas->map(function ($lectura) {
                    return [
                        'id' => $lectura->id,
                        'valor' => $lectura->valor,
                        'created_at' => $lectura->created_at->toIso8601String(),
                        'formatted_date' => $lectura->created_at->format('Y-m-d'),
                        'formatted_time' => $lectura->created_at->format('H:i:s')
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener datos: ' . $e->getMessage(),
                'lecturas' => []
            ], 500);
        }
    }
}
