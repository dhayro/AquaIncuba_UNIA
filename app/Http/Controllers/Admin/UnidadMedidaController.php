<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class UnidadMedidaController extends Controller
{
    /**
     * Listar unidades de medida
     */
    public function index()
    {
        return view('admin.unidades-medida.index', [
            'title' => 'Gestión de Unidades de Medida',
            'catName' => 'unidades-medida',
        ]);
    }

    /**
     * Obtener datos de unidades de medida en formato JSON para DataTables
     */
    public function getUnidadesMedidaData()
    {
        try {
            // Query optimizada
            $unidades = UnidadMedida::query()
                ->select('id', 'nombre', 'simbolo', 'descripcion', 'activo')
                ->orderBy('nombre', 'asc')
                ->get();
            
            $data = [];
            foreach ($unidades as $unidad) {
                $rowData = $this->generateUnidadRow($unidad);
                
                $data[] = [
                    $rowData['nombre'],
                    $rowData['simbolo'],
                    $rowData['descripcion'],
                    $rowData['estado'],
                    $rowData['acciones']
                ];
            }

            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => $unidades->count(),
                'recordsFiltered' => $unidades->count(),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getUnidadesMedidaData: ' . $e->getMessage());
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
     * Generar fila HTML para una unidad de medida
     */
    private function generateUnidadRow($unidad)
    {
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-unidad" data-unidad-id="' . $unidad->id . '" data-bs-toggle="modal" data-bs-target="#editUnidadModal" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-unidad ms-2" data-unidad-id="' . $unidad->id . '" data-unidad-nombre="' . htmlspecialchars($unidad->nombre) . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';

        $badgeClass = $unidad->activo ? 'bg-success' : 'bg-danger';
        $badgeText = $unidad->activo ? 'Activo' : 'Inactivo';
        $estado = '<span class="badge ' . $badgeClass . ' btn-toggle-estado" data-unidad-id="' . $unidad->id . '" style="cursor: pointer;" title="Click para cambiar estado">' . $badgeText . '</span>';

        return [
            'nombre' => '<span class="fw-bold">' . ucfirst($unidad->nombre) . '</span>',
            'simbolo' => '<span class="badge bg-primary">' . $unidad->simbolo . '</span>',
            'descripcion' => $unidad->descripcion ?? '<span class="text-muted">—</span>',
            'estado' => $estado,
            'acciones' => $editBtn . ' ' . $deleteBtn
        ];
    }

    /**
     * Crear unidad de medida
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_medida,nombre',
            'simbolo' => 'required|string|max:50|unique:unidades_medida,simbolo',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $validated['activo'] = true;
            $unidad = UnidadMedida::create($validated);

            if ($request->expectsJson()) {
                $rowData = $this->generateUnidadRow($unidad);
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad de medida creada correctamente',
                    'unidad' => $unidad,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('unidades-medida.index')->with('success', 'Unidad creada exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la unidad: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear la unidad');
        }
    }

    /**
     * Editar unidad de medida
     */
    public function edit($id)
    {
        try {
            $unidad = UnidadMedida::findOrFail($id);
            return response()->json([
                'success' => true,
                'id' => $unidad->id,
                'nombre' => $unidad->nombre,
                'simbolo' => $unidad->simbolo,
                'descripcion' => $unidad->descripcion,
                'activo' => $unidad->activo,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la unidad: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualizar unidad de medida
     */
    public function update(Request $request, $id)
    {
        $unidad = UnidadMedida::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:unidades_medida,nombre,' . $id,
            'simbolo' => 'required|string|max:50|unique:unidades_medida,simbolo,' . $id,
            'descripcion' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        try {
            $unidad->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Unidad actualizada correctamente',
                    'unidad' => $unidad
                ]);
            }

            return redirect()->route('unidades-medida.index')->with('success', 'Unidad actualizada exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar la unidad');
        }
    }

    /**
     * Eliminar unidad de medida
     */
    public function destroy($id)
    {
        try {
            $unidad = UnidadMedida::findOrFail($id);
            $unidadNombre = $unidad->nombre;
            $unidad->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Unidad '$unidadNombre' eliminada correctamente"
                ]);
            }

            return redirect()->route('unidades-medida.index')->with('success', 'Unidad eliminada exitosamente');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar la unidad');
        }
    }

    /**
     * Toggle estado (activo/inactivo) de unidad de medida
     */
    public function toggleEstado($id)
    {
        try {
            $unidad = UnidadMedida::findOrFail($id);
            $unidad->activo = !$unidad->activo;
            $unidad->save();

            return response()->json([
                'success' => true,
                'activo' => $unidad->activo,
                'estado' => $unidad->activo ? 'Activo' : 'Inactivo',
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
