<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoSensor;
use Illuminate\Http\Request;

class TipoSensorController extends Controller
{
    /**
     * Listar tipos de sensores
     */
    public function index()
    {
        return view('admin.tipos-sensores.index', [
            'title' => 'Gestión de Tipos de Sensores',
            'catName' => 'tipos-sensores',
        ]);
    }

    /**
     * Obtener datos de tipos de sensores en formato JSON para DataTables
     */
    public function getTiposSensoresData()
    {
        try {
            // Query optimizada
            $tipos = TipoSensor::query()
                ->select('id', 'nombre', 'descripcion', 'activo')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $data = [];
            foreach ($tipos as $tipo) {
                $rowData = $this->generateTipoRow($tipo);
                
                $data[] = [
                    $rowData['nombre'],
                    $rowData['descripcion'],
                    $rowData['estado'],
                    $rowData['acciones']
                ];
            }

            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => $tipos->count(),
                'recordsFiltered' => $tipos->count(),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getTiposSensoresData: ' . $e->getMessage());
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
     * Generar fila HTML para un tipo de sensor
     */
    private function generateTipoRow($tipo)
    {
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-tipo" data-tipo-id="' . $tipo->id . '" data-bs-toggle="modal" data-bs-target="#editTipoModal" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-tipo ms-2" data-tipo-id="' . $tipo->id . '" data-tipo-nombre="' . htmlspecialchars($tipo->nombre) . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';

        $badgeClass = $tipo->activo ? 'bg-success' : 'bg-danger';
        $badgeText = $tipo->activo ? 'Activo' : 'Inactivo';
        $estado = '<span class="badge ' . $badgeClass . ' btn-toggle-tipo-estado" data-tipo-id="' . $tipo->id . '" style="cursor: pointer;" title="Click para cambiar estado">' . $badgeText . '</span>';

        return [
            'nombre' => '<span class="fw-bold">' . ucfirst($tipo->nombre) . '</span>',
            'descripcion' => $tipo->descripcion ?? '<span class="text-muted">—</span>',
            'estado' => $estado,
            'acciones' => $editBtn . ' ' . $deleteBtn
        ];
    }

    /**
     * Crear tipo de sensor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_sensores,nombre',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $validated['activo'] = true;

            $tipo = TipoSensor::create($validated);

            if ($request->expectsJson()) {
                $rowData = $this->generateTipoRow($tipo);
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de sensor creado correctamente',
                    'tipo' => $tipo,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('tipos-sensores.index')->with('success', 'Tipo de sensor creado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el tipo: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear el tipo de sensor');
        }
    }

    /**
     * Editar tipo de sensor
     */
    public function edit($id)
    {
        try {
            $tipo = TipoSensor::findOrFail($id);
            return response()->json([
                'success' => true,
                'id' => $tipo->id,
                'nombre' => $tipo->nombre,
                'descripcion' => $tipo->descripcion,
                'activo' => $tipo->activo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el tipo: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar tipo de sensor
     */
    public function update(Request $request, $id)
    {
        $tipo = TipoSensor::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_sensores,nombre,' . $id,
            'descripcion' => 'nullable|string',
        ]);

        try {
            $tipo->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipo de sensor actualizado correctamente',
                    'tipo' => $tipo
                ]);
            }

            return redirect()->route('tipos-sensores.index')->with('success', 'Tipo actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar el tipo');
        }
    }

    /**
     * Eliminar tipo de sensor
     */
    public function destroy($id)
    {
        try {
            $tipo = TipoSensor::findOrFail($id);
            $tipoNombre = $tipo->nombre;
            $tipo->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Tipo '$tipoNombre' eliminado correctamente"
                ]);
            }

            return redirect()->route('tipos-sensores.index')->with('success', 'Tipo eliminado exitosamente');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar el tipo');
        }
    }

    /**
     * Toggle estado (activo/inactivo) de tipo sensor
     */
    public function toggleEstado($id)
    {
        try {
            $tipo = TipoSensor::findOrFail($id);
            $tipo->activo = !$tipo->activo;
            $tipo->save();

            return response()->json([
                'success' => true,
                'activo' => $tipo->activo,
                'estado' => $tipo->activo ? 'Activo' : 'Inactivo',
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 400);
        }
    }
}
