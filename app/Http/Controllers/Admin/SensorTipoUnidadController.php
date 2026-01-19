<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorTipoUnidad;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorTipoUnidadController extends Controller
{
    /**
     * Crear una asignación de tipo a sensor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sensor_id' => 'required|exists:sensores,id',
            'tipo_sensor_ids' => 'required|array',
            'tipo_sensor_ids.*' => 'exists:tipo_sensores,id',
            'unidad_medida_id' => 'required|exists:unidades_medida,id',
            'minimo_optimo' => 'nullable|numeric',
            'maximo_optimo' => 'nullable|numeric',
            'minimo_critico' => 'nullable|numeric',
            'maximo_critico' => 'nullable|numeric',
        ]);

        try {
            $sensor = Sensor::find($validated['sensor_id']);
            
            if ($sensor->id_empresa !== auth()->user()->id_empresa) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            $asignaciones = [];
            foreach ($validated['tipo_sensor_ids'] as $tipoId) {
                // Evitar duplicados
                $existe = SensorTipoUnidad::where('sensor_id', $validated['sensor_id'])
                    ->where('tipo_sensor_id', $tipoId)
                    ->where('unidad_medida_id', $validated['unidad_medida_id'])
                    ->exists();
                
                if (!$existe) {
                    $asignaciones[] = SensorTipoUnidad::create([
                        'sensor_id' => $validated['sensor_id'],
                        'tipo_sensor_id' => $tipoId,
                        'unidad_medida_id' => $validated['unidad_medida_id'],
                        'minimo_optimo' => $validated['minimo_optimo'] ?? null,
                        'maximo_optimo' => $validated['maximo_optimo'] ?? null,
                        'minimo_critico' => $validated['minimo_critico'] ?? null,
                        'maximo_critico' => $validated['maximo_critico'] ?? null,
                        'activo' => true,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($asignaciones) . ' tipos asignados correctamente',
                'asignaciones' => $asignaciones
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en SensorTipoUnidadController@store: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar tipos: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Actualizar una asignación
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'minimo_optimo' => 'nullable|numeric',
            'maximo_optimo' => 'nullable|numeric',
            'minimo_critico' => 'nullable|numeric',
            'maximo_critico' => 'nullable|numeric',
        ]);

        try {
            $asignacion = SensorTipoUnidad::find($id);
            
            if (!$asignacion) {
                return response()->json(['success' => false, 'message' => 'Asignación no encontrada'], 404);
            }

            $sensor = $asignacion->sensor;
            if ($sensor->id_empresa !== auth()->user()->id_empresa) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            $asignacion->update([
                'minimo_optimo' => $validated['minimo_optimo'] ?? $asignacion->minimo_optimo,
                'maximo_optimo' => $validated['maximo_optimo'] ?? $asignacion->maximo_optimo,
                'minimo_critico' => $validated['minimo_critico'] ?? $asignacion->minimo_critico,
                'maximo_critico' => $validated['maximo_critico'] ?? $asignacion->maximo_critico,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asignación actualizada correctamente',
                'asignacion' => $asignacion
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en SensorTipoUnidadController@update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar asignación'
            ], 400);
        }
    }

    /**
     * Eliminar una asignación
     */
    public function destroy($id)
    {
        try {
            $asignacion = SensorTipoUnidad::find($id);
            
            if (!$asignacion) {
                return response()->json(['success' => false, 'message' => 'Asignación no encontrada'], 404);
            }

            $sensor = $asignacion->sensor;
            if ($sensor->id_empresa !== auth()->user()->id_empresa) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            $asignacion->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asignación eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en SensorTipoUnidadController@destroy: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar asignación'
            ], 400);
        }
    }
}
