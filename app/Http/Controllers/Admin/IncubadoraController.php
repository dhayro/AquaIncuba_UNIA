<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incubadora;
use Illuminate\Http\Request;

class IncubadoraController extends Controller
{
    /**
     * Listar incubadoras - Nueva versión con AJAX
     */
    public function index()
    {
        return view('admin.incubadoras.index-v2', [
            'title' => 'Gestión de Incubadoras',
            'catName' => 'incubadoras',
        ]);
    }

    /**
     * Obtener datos de incubadoras en formato JSON para DataTables
     */
    public function getIncubadorasData()
    {
        try {
            $empresaId = auth()->user()?->id_empresa;
            
            $incubadoras = Incubadora::query()
                ->with('incubadoraSensores')
                ->where('id_empresa', $empresaId)
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function ($incubadora) {
                    return [
                        'id' => $incubadora->id,
                        'nombre' => $incubadora->nombre,
                        'codigo' => $incubadora->codigo,
                        'capacidad_tanque' => $incubadora->capacidad_tanque ?? 'N/A',
                        'sensores_count' => $incubadora->incubadoraSensores->count(),
                        'estado' => '<span class="badge ' . 
                                  ($incubadora->activo ? 'bg-success' : 'bg-danger') . 
                                  ' btn-toggle-incubadora-estado" data-incubadora-id="' . $incubadora->id . '" style="cursor: pointer;" title="Click para cambiar estado">' . 
                                  ($incubadora->activo ? 'Activo' : 'Inactivo') . '</span>',
                        'acciones' => $this->generateIncubadoraActions($incubadora->id, $incubadora->nombre)
                    ];
                });
            
            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => $incubadoras->count(),
                'recordsFiltered' => $incubadoras->count(),
                'data' => $incubadoras->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getIncubadorasData: ' . $e->getMessage());
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
     * Generar botones de acciones para una fila
     */
    private function generateIncubadoraActions($incubadoraId, $incubadoraNombre)
    {
        $viewBtn = '<button type="button" class="btn btn-sm btn-outline-primary btn-view-incubadora" data-incubadora-id="' . $incubadoraId . '" title="Ver detalles" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>';
        
        $asignarBtn = '<button type="button" class="btn btn-sm btn-outline-info btn-asignar-sensores-incubadora ms-2" data-incubadora-id="' . $incubadoraId . '" title="Asignar sensores" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    </button>';
        
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-incubadora ms-2" data-incubadora-id="' . $incubadoraId . '" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-incubadora ms-2" data-incubadora-id="' . $incubadoraId . '" data-incubadora-nombre="' . $incubadoraNombre . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';
        
        return $viewBtn . ' ' . $asignarBtn . ' ' . $editBtn . ' ' . $deleteBtn;
    }

    /**
     * Crear incubadora
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:incubadoras,codigo',
            'descripcion' => 'nullable|string',
            'capacidad_tanque' => 'nullable|integer|min:1',
            'ubicacion' => 'nullable|string',
            'especificaciones' => 'nullable|string',
        ]);

        try {
            $validated['id_empresa'] = auth()->user()->id_empresa;

            $incubadora = Incubadora::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Incubadora creada correctamente',
                    'incubadora' => $incubadora
                ]);
            }

            return redirect()->route('incubadoras.index')->with('success', 'Incubadora creada exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la incubadora: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear la incubadora');
        }
    }

    /**
     * Obtener incubadora para editar
     */
    public function show($id)
    {
        $empresaId = auth()->user()?->id_empresa;
        $incubadora = Incubadora::find($id);
        
        if (!$incubadora) {
            return response()->json(['error' => 'Incubadora no encontrada'], 404);
        }
        
        if ($empresaId && $incubadora->id_empresa !== $empresaId) {
            return response()->json(['error' => 'No tienes permiso'], 403);
        }
        
        return response()->json([
            'id' => $incubadora->id,
            'nombre' => $incubadora->nombre,
            'codigo' => $incubadora->codigo,
            'descripcion' => $incubadora->descripcion,
            'capacidad_tanque' => $incubadora->capacidad_tanque,
            'ubicacion' => $incubadora->ubicacion,
            'especificaciones' => $incubadora->especificaciones,
            'estado' => $incubadora->estado,
        ]);
    }

    /**
     * Actualizar incubadora
     */
    public function update(Request $request, Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No tienes permiso'], 403);
            }
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:incubadoras,codigo,' . $incubadora->id,
            'descripcion' => 'nullable|string',
            'capacidad_tanque' => 'nullable|integer|min:1',
            'ubicacion' => 'nullable|string',
            'especificaciones' => 'nullable|string',
        ]);

        try {
            $incubadora->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Incubadora actualizada correctamente',
                    'incubadora' => $incubadora
                ]);
            }

            return redirect()->route('incubadoras.index')->with('success', 'Incubadora actualizada exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar la incubadora');
        }
    }

    /**
     * Eliminar incubadora
     */
    public function destroy(Request $request, Incubadora $incubadora)
    {
        try {
            if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'No tienes permiso'], 403);
                }
                abort(403);
            }

            $incubadora->incubadoraSensores()->delete();
            $incubadora->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Incubadora eliminada correctamente'
                ]);
            }

            return redirect()->route('incubadoras.index')->with('success', 'Incubadora eliminada exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar la incubadora');
        }
    }

    /**
     * Toggle estado de incubadora
     */
    public function toggleEstado(Request $request, Incubadora $incubadora)
    {
        try {
            if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
                return response()->json(['error' => 'No tienes permiso'], 403);
            }

            // Toggle el estado boolean
            $nuevoEstado = !$incubadora->activo;
            $incubadora->update(['activo' => $nuevoEstado]);

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'activo' => $nuevoEstado
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Asignar sensores a incubadora
     */
    public function guardarSensores(Request $request, Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            return response()->json(['error' => 'No tienes permiso'], 403);
        }

        $validated = $request->validate([
            'sensores' => 'nullable|array',
            'sensores.*' => 'exists:sensores,id',
        ]);

        try {
            $incubadora->incubadoraSensores()->delete();
            
            if (!empty($validated['sensores'])) {
                foreach ($validated['sensores'] as $index => $sensorId) {
                    $incubadora->incubadoraSensores()->create([
                        'id_sensor' => $sensorId,
                        'orden_posicion' => $index,
                        'activo' => true,
                        'fecha_instalacion' => now(),
                    ]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sensores asignados correctamente'
                ]);
            }

            return redirect()->route('incubadoras.index')->with('success', 'Sensores asignados exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al asignar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al asignar sensores');
        }
    }

    /**
     * Obtener sensores disponibles para una incubadora
     */
    public function getSensoresDisponibles(Incubadora $incubadora)
    {
        try {
            $empresaId = auth()->user()?->id_empresa;

            if ($incubadora->id_empresa !== $empresaId) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $sensoresAsignados = $incubadora->incubadoraSensores()
                ->pluck('id_sensor')
                ->toArray();

            $sensores = \App\Models\Sensor::where('id_empresa', $empresaId)
                ->where('estado', 'ACTIVO')
                ->orderBy('nombre', 'asc')
                ->get()
                ->map(function ($sensor) use ($sensoresAsignados) {
                    return [
                        'id' => $sensor->id,
                        'nombre' => $sensor->nombre . ' (' . $sensor->codigo . ')',
                        'seleccionado' => in_array($sensor->id, $sensoresAsignados)
                    ];
                });

            return response()->json($sensores->values());
        } catch (\Exception $e) {
            \Log::error('Error en getSensoresDisponibles: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

