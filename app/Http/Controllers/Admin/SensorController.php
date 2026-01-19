<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\SensorTipoUnidad;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Listar sensores
     */
    public function index()
    {
        // Usar vista actualizada que soporta junction table
        return view('admin.sensores.index-v2', [
            'title' => 'Gestión de Sensores',
            'catName' => 'sensores',
        ]);
    }

    /**
     * Vista para asignar tipos a sensores
     */
    public function asignarTipos()
    {
        return view('admin.sensores.asignar-tipos', [
            'title' => 'Asignar Tipos de Sensores',
            'catName' => 'sensores',
        ]);
    }

    /**
     * Obtener datos de sensores en formato JSON para DataTables
     * Adaptado para mostrar nueva arquitectura de junction table
     */
    public function getSensoresData()
    {
        try {
            $empresaId = auth()->user()?->id_empresa;
            
            // Query con conteo de tipos asignados
            $sensores = Sensor::query()
                ->with('sensorTipoUnidades.tipoSensor')
                ->where('id_empresa', $empresaId)
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function ($sensor) {
                    return [
                        'id' => $sensor->id,
                        'nombre' => $sensor->nombre,
                        'codigo' => $sensor->codigo,
                        'tema_mqtt' => $sensor->tema_mqtt,
                        'tipos_count' => $sensor->sensorTipoUnidades->count(),
                        'estado' => '<span class="badge ' . 
                                  ($sensor->estado === 'activo' ? 'bg-success' : 'bg-danger') . 
                                  ' btn-toggle-sensor-estado" data-sensor-id="' . $sensor->id . '" style="cursor: pointer;" title="Click para cambiar estado">' . 
                                  ($sensor->estado === 'activo' ? 'Activo' : 'Inactivo') . '</span>',
                        'acciones' => $this->generateSensorActions($sensor->id, $sensor->nombre)
                    ];
                });
            
            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => $sensores->count(),
                'recordsFiltered' => $sensores->count(),
                'data' => $sensores->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getSensoresData: ' . $e->getMessage());
            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Obtener tipos y unidades de un sensor (para el modal de visualización)
     */
    public function getTiposUnidades($sensorId)
    {
        try {
            $sensor = Sensor::find($sensorId);
            
            if (!$sensor) {
                return response()->json(['error' => 'Sensor no encontrado'], 404);
            }

            $tiposUnidades = SensorTipoUnidad::where('sensor_id', $sensorId)
                ->with(['tipoSensor', 'unidadMedida'])
                ->where('activo', true)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'tipo' => $item->tipoSensor?->nombre ?? 'Sin tipo',
                        'unidad' => $item->unidadMedida?->simbolo ?? '—',
                        'minimo_optimo' => $item->minimo_optimo,
                        'maximo_optimo' => $item->maximo_optimo,
                        'minimo_critico' => $item->minimo_critico,
                        'maximo_critico' => $item->maximo_critico,
                        'factor_calibracion' => $item->factor_calibracion ?? 1,
                        'decimales' => $item->decimales ?? 2
                    ];
                });

            return response()->json($tiposUnidades);
        } catch (\Exception $e) {
            \Log::error('Error en getTiposUnidades: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Generar botones de acciones para una fila
     */
    private function generateSensorActions($sensorId, $sensorNombre)
    {
        $viewBtn = '<button type="button" class="btn btn-sm btn-outline-primary btn-view-sensor" data-sensor-id="' . $sensorId . '" title="Ver detalles" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>';
        
        $asignarBtn = '<button type="button" class="btn btn-sm btn-outline-info btn-asignar-tipos-sensor ms-2" data-sensor-id="' . $sensorId . '" title="Asignar tipos" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </button>';
        
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-sensor ms-2" data-sensor-id="' . $sensorId . '" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-sensor ms-2" data-sensor-id="' . $sensorId . '" data-sensor-nombre="' . $sensorNombre . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';
        
        return $viewBtn . ' ' . $asignarBtn . ' ' . $editBtn . ' ' . $deleteBtn;
    }

    /**
     * Crear sensor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sensores,codigo',
            'descripcion' => 'nullable|string',
            'tema_mqtt' => 'nullable|string',
            'id_dispositivo_mqtt' => 'nullable|exists:dispositivos_mqtt,id',
        ]);

        try {
            $validated['id_empresa'] = auth()->user()->id_empresa;
            $validated['tema_mqtt'] = $validated['tema_mqtt'] ?? 'sensor/' . $validated['codigo'];

            $sensor = Sensor::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sensor creado correctamente',
                    'sensor' => $sensor
                ]);
            }

            return redirect()->route('sensores.index')->with('success', 'Sensor creado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el sensor: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear el sensor');
        }
    }

    /**
     * Obtener sensor para editar
     */
    public function show($id)
    {
        $empresaId = auth()->user()?->id_empresa;
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            return response()->json(['error' => 'Sensor no encontrado'], 404);
        }
        
        if ($empresaId && $sensor->id_empresa !== $empresaId) {
            return response()->json(['error' => 'No tienes permiso'], 403);
        }
        
        return response()->json([
            'id' => $sensor->id,
            'nombre' => $sensor->nombre,
            'codigo' => $sensor->codigo,
            'descripcion' => $sensor->descripcion,
            'tema_mqtt' => $sensor->tema_mqtt,
            'id_dispositivo_mqtt' => $sensor->id_dispositivo_mqtt,
            'estado' => $sensor->estado === 'activo' ? 'Activo' : 'Inactivo'
        ]);
    }

    /**
     * Obtener sensor para formulario de edición (alias de show)
     */
    public function edit($id)
    {
        return $this->show($id);
    }

    /**
     * Actualizar sensor
     */
    public function update(Request $request, $id)
    {
        $empresaId = auth()->user()?->id_empresa;
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Sensor no encontrado'], 404);
            }
            return back()->with('error', 'Sensor no encontrado');
        }
        
        if ($empresaId && $sensor->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sensores,codigo,' . $sensor->id,
            'descripcion' => 'nullable|string',
            'tema_mqtt' => 'nullable|string',
            'id_dispositivo_mqtt' => 'nullable|exists:dispositivos_mqtt,id',
        ]);

        try {
            $sensor->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sensor actualizado correctamente',
                    'sensor' => $sensor
                ]);
            }

            return redirect()->route('sensores.index')->with('success', 'Sensor actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el sensor: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar el sensor');
        }
    }

    /**
     * Eliminar sensor
     */
    public function destroy(Request $request, $id)
    {
        $empresaId = auth()->user()?->id_empresa;
        $sensor = Sensor::find($id);
        
        if (!$sensor) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Sensor no encontrado'], 404);
            }
            return back()->with('error', 'Sensor no encontrado');
        }
        
        if ($empresaId && $sensor->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        try {
            // Eliminar relaciones con junction table
            $sensor->sensorTipoUnidades()->delete();
            
            $sensor->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sensor eliminado correctamente'
                ]);
            }

            return redirect()->route('sensores.index')->with('success', 'Sensor eliminado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el sensor: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar el sensor');
        }
    }

    /**
     * Toggle estado (activo/inactivo) de sensor
     */
    public function toggleEstado($id)
    {
        try {
            $empresaId = auth()->user()?->id_empresa;
            $sensor = Sensor::findOrFail($id);

            if ($empresaId && $sensor->id_empresa !== $empresaId) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }

            // Cambiar el estado entre 'activo' e 'inactivo'
            $nuevoEstado = $sensor->estado === 'activo' ? 'inactivo' : 'activo';
            $sensor->estado = $nuevoEstado;
            $sensor->save();

            return response()->json([
                'success' => true,
                'estado' => $nuevoEstado,
                'estadoLabel' => $nuevoEstado === 'activo' ? 'Activo' : 'Inactivo',
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en toggleEstado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 400);
        }
    }
}

