<?php

namespace App\Http\Controllers\Admin;

use App\Models\EstudioCalidadAgua;
use App\Models\MqttLectura;
use App\Models\SensorParametroMapping;
use App\Models\Sensor;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    /**
     * Mostrar dashboard principal con datos en tiempo real
     */
    public function index(): View
    {
        // Obtener estudios activos (en_progreso Y sin vencer)
        $hoy = now()->format('Y-m-d');
        
        $estudios = EstudioCalidadAgua::where('estado', 'en_progreso')
            ->where(function ($query) use ($hoy) {
                // Estudios sin fecha_fin O con fecha_fin en el futuro
                $query->whereNull('fecha_fin')
                      ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->with('incubadoras.sensores')
            ->get();

        // Obtener últimas lecturas por parámetro
        $ultimasLecturas = MqttLectura::with('estudio')
            ->latest('created_at')
            ->limit(50)
            ->get();

        // Estadísticas
        $stats = [
            'total_ciclos' => \App\Models\CicloMqttCrudo::count(),
            'ciclos_procesados' => \App\Models\CicloMqttCrudo::where('estado', 'PROCESADO')->count(),
            'total_lecturas' => MqttLectura::count(),
            'sensores_activos' => Sensor::count(),
        ];

        // Para cada estudio, obtener lecturas de sus sensores
        $lecturasPorEstudio = [];
        foreach ($estudios as $estudio) {
            $incubadorasData = [];
            foreach ($estudio->incubadoras as $incubadora) {
                $sensoresData = [];
                
                foreach ($incubadora->sensores as $sensor) {
                    // Obtener mappings para este sensor en este estudio
                    $mappings = SensorParametroMapping::where('sensor_id', $sensor->id)
                        ->where('id_estudio', $estudio->id)
                        ->get();

                    // Por cada parámetro/mapping, crear una entrada separada
                    foreach ($mappings as $mapping) {
                        // Obtener última lectura para este parámetro
                        $ultimaLectura = MqttLectura::where('id_estudio', $estudio->id)
                            ->where('id_parametro', $mapping->id_parametro)
                            ->latest('created_at')
                            ->first();

                        if ($ultimaLectura) {
                            $sensoresData[] = [
                                'id' => $sensor->id,
                                'nombre' => $sensor->nombre,
                                'parametro' => $mapping->parametro->tipoSensor->nombre ?? 'N/A',
                                'valor' => $ultimaLectura->valor,
                                'unidad' => $mapping->parametro->unidadMedida->nombre ?? 'N/A',
                                'fecha' => $ultimaLectura->created_at->format('H:i:s'),
                            ];
                        }
                    }
                }
                
                if (count($sensoresData) > 0) {
                    $incubadorasData[] = [
                        'nombre' => $incubadora->nombre,
                        'sensores' => $sensoresData,
                    ];
                }
            }
            
            if (count($incubadorasData) > 0) {
                $lecturasPorEstudio[$estudio->id] = [
                    'nombre' => $estudio->nombre,
                    'incubadoras' => $incubadorasData,
                ];
            }
        }

        return view('admin.dashboard.index', [
            'estudios' => $estudios,
            'stats' => $stats,
            'lecturasPorEstudio' => $lecturasPorEstudio,
            'ultimasLecturas' => $ultimasLecturas,
        ]);
    }

    /**
     * API: Obtener datos en tiempo real (JSON)
     */
    public function api()
    {
        $hoy = now()->format('Y-m-d');
        
        $estudios = EstudioCalidadAgua::where('estado', 'en_progreso')
            ->where(function ($query) use ($hoy) {
                $query->whereNull('fecha_fin')
                      ->orWhereDate('fecha_fin', '>=', $hoy);
            })
            ->get();

        $datos = [];
        foreach ($estudios as $estudio) {
            $incubadorasData = [];
            
            // Para cada incubadora del estudio
            foreach ($estudio->incubadoras as $incubadora) {
                $sensoresData = [];
                
                // El sensor corresponde a la incubadora con el mismo ID
                $sensorId = $incubadora->id;
                
                // Obtener todos los mappings para este sensor en este estudio
                $mappings = SensorParametroMapping::where('sensor_id', $sensorId)
                    ->where('id_estudio', $estudio->id)
                    ->get();

                if ($mappings->isEmpty()) {
                    continue;
                }

                $sensor = Sensor::find($sensorId);
                if (!$sensor) continue;

                // Por cada parámetro/mapping, crear una entrada separada
                foreach ($mappings as $mapping) {
                    $ultimaLectura = MqttLectura::where('id_estudio', $estudio->id)
                        ->where('id_parametro', $mapping->id_parametro)
                        ->latest('created_at')
                        ->first();

                    if ($ultimaLectura) {
                        $sensoresData[] = [
                            'sensor_id' => $sensor->id,
                            'nombre' => $sensor->nombre,
                            'parametro' => $mapping->parametro->tipoSensor->nombre ?? 'N/A',
                            'valor' => $ultimaLectura->valor ?? 'SIN DATO', // Mostrar "SIN DATO" si es NULL
                            'unidad' => $mapping->parametro->unidadMedida->nombre ?? 'N/A',
                            'timestamp' => $ultimaLectura->created_at,
                            'es_null' => $ultimaLectura->valor === null, // Indicador de dato inválido/sanitizado
                        ];
                    }
                }
                
                if (count($sensoresData) > 0) {
                    $incubadorasData[] = [
                        'incubadora_id' => $incubadora->id,
                        'nombre' => $incubadora->nombre,
                        'sensores' => $sensoresData,
                    ];
                }
            }
            
            if (count($incubadorasData) > 0) {
                $datos[] = [
                    'estudio_id' => $estudio->id,
                    'nombre' => $estudio->nombre,
                    'estado' => strtolower($estudio->estado),
                    'incubadoras' => $incubadorasData,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'estudios' => $datos,
            'stats' => [
                'total_ciclos' => \App\Models\CicloMqttCrudo::count(),
                'ciclos_procesados' => \App\Models\CicloMqttCrudo::where('estado', 'PROCESADO')->count(),
                'total_lecturas' => MqttLectura::count(),
            ],
            'timestamp' => now(),
        ]);
    }
}
