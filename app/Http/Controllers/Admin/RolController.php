<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use App\Models\Menu;
use App\Models\PermisoMenuRol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    /**
     * Listar roles
     */
    public function index()
    {
        return view('admin.roles.index', [
            'title' => 'Gestión de Roles',
            'catName' => 'roles',
        ]);
    }

    /**
     * Obtener datos de roles en formato JSON para DataTables
     */
    public function getRolesData()
    {
        try {
            $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
            
            $query = Rol::query();
            if ($empresaId) {
                $query->where('id_empresa', $empresaId);
            }
            $roles = $query->get();
            
            $data = [];
            foreach ($roles as $rol) {
                // Contar solo los permisos que tienen al menos un permiso activo (1)
                $permCount = PermisoMenuRol::where('id_rol', $rol->id)
                    ->where(function($q) {
                        $q->where('puede_ver', 1)
                          ->orWhere('puede_crear', 1)
                          ->orWhere('puede_editar', 1)
                          ->orWhere('puede_eliminar', 1);
                    })
                    ->count();
                
                $rowData = $this->generateRoleRow($rol, $permCount);
                
                $data[] = [
                    $rowData['nombre'],
                    $rowData['descripcion'],
                    $rowData['permisos'],
                    $rowData['acciones']
                ];
            }

            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'data' => $data
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getRolesData: ' . $e->getMessage());
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
     * Generar fila HTML para un rol
     */
    private function generateRoleRow($rol, $permCount = 0)
    {
        $permisosBtn = '<a href="' . route('roles.permisos.edit', $rol->id) . '" class="btn btn-sm btn-outline-info" title="Gestionar permisos">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"></path></svg>
                        </a>';
        
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-role" data-role-id="' . $rol->id . '" data-bs-toggle="modal" data-bs-target="#editRoleModal" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-role ms-2" data-role-id="' . $rol->id . '" data-bs-toggle="modal" data-bs-target="#deleteRoleModal" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';

        return [
            'nombre' => '<span class="fw-bold">' . ucfirst($rol->nombre) . '</span>',
            'descripcion' => $rol->descripcion ?? '<span class="text-muted">—</span>',
            'permisos' => '<span class="badge bg-light text-dark">' . $permCount . '</span>',
            'acciones' => $permisosBtn . ' ' . $editBtn . ' ' . $deleteBtn
        ];
    }

    /**
     * Crear rol
     */
    public function create()
    {
        return view('admin.roles.create', [
            'title' => 'Crear Rol',
            'catName' => 'roles',
        ]);
    }

    /**
     * Guardar nuevo rol
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:roles,nombre',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $validated['id_empresa'] = auth()->user()->id_empresa;

            $rol = Rol::create($validated);

            if ($request->expectsJson()) {
                $rowData = $this->generateRoleRow($rol, 0);
                return response()->json([
                    'success' => true,
                    'message' => 'Rol creado correctamente',
                    'rol' => $rol,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el rol: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear el rol');
        }
    }

    /**
     * Editar rol
     */
    public function edit($id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $rol = Rol::find($id);
        
        if (!$rol) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }
        
        if ($empresaId && $rol->id_empresa !== $empresaId) {
            return response()->json(['error' => 'No tienes permiso para editar este rol'], 403);
        }
        
        return response()->json([
            'id' => $rol->id,
            'nombre' => $rol->nombre,
            'descripcion' => $rol->descripcion
        ]);
    }

    /**
     * Actualizar rol
     */
    public function update(Request $request, $id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $rol = Rol::find($id);
        
        if (!$rol) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
            }
            return back()->with('error', 'Rol no encontrado');
        }
        
        if ($empresaId && $rol->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|unique:roles,nombre,' . $rol->id,
            'descripcion' => 'nullable|string',
        ]);

        try {
            $rol->update($validated);

            if ($request->expectsJson()) {
                // Contar solo los permisos que tienen al menos un permiso activo (1)
                $permCount = PermisoMenuRol::where('id_rol', $rol->id)
                    ->where(function($q) {
                        $q->where('puede_ver', 1)
                          ->orWhere('puede_crear', 1)
                          ->orWhere('puede_editar', 1)
                          ->orWhere('puede_eliminar', 1);
                    })
                    ->count();
                
                $rowData = $this->generateRoleRow($rol, $permCount);
                return response()->json([
                    'success' => true,
                    'message' => 'Rol actualizado correctamente',
                    'rol' => $rol,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el rol: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar el rol');
        }
    }

    /**
     * Eliminar rol
     */
    public function destroy(Request $request, $id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $rol = Rol::find($id);
        
        if (!$rol) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Rol no encontrado'], 404);
            }
            return back()->with('error', 'Rol no encontrado');
        }
        
        if ($empresaId && $rol->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        try {
            // Eliminar permisos asociados
            PermisoMenuRol::where('id_rol', $rol->id)->delete();
            
            $rol->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rol eliminado correctamente'
                ]);
            }

            return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el rol: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar el rol');
        }
    }

    /**
     * Editar permisos del rol
     */
    public function editPermisos(Rol $rol)
    {
        $empresaId = auth()->user()->id_empresa;
        $menus = Menu::where('id_empresa', $empresaId)
            ->orderBy('orden')
            ->get();

        $permisosActuales = PermisoMenuRol::where('id_rol', $rol->id)
            ->pluck('id_menu')
            ->toArray();

        return view('admin.roles.permisos', [
            'rol' => $rol,
            'menus' => $menus,
            'permisosActuales' => $permisosActuales,
            'title' => 'Gestionar Permisos - ' . $rol->nombre,
            'catName' => 'roles',
        ]);
    }

    /**
     * Actualizar permisos del rol
     */
    public function actualizarPermisos(Request $request, Rol $rol)
    {
        $validated = $request->validate([
            'permisos' => 'nullable|array',
            'permisos.*.menu_id' => 'required|exists:menus,id',
            'permisos.*.puede_ver' => 'boolean',
            'permisos.*.puede_crear' => 'boolean',
            'permisos.*.puede_editar' => 'boolean',
            'permisos.*.puede_eliminar' => 'boolean',
        ]);

        // Eliminar permisos existentes
        PermisoMenuRol::where('id_rol', $rol->id)->delete();

        // Crear nuevos permisos
        if ($validated['permisos'] ?? false) {
            foreach ($validated['permisos'] as $permiso) {
                PermisoMenuRol::create([
                    'id_rol' => $rol->id,
                    'id_menu' => $permiso['menu_id'],
                    'puede_ver' => $permiso['puede_ver'] ?? false,
                    'puede_crear' => $permiso['puede_crear'] ?? false,
                    'puede_editar' => $permiso['puede_editar'] ?? false,
                    'puede_eliminar' => $permiso['puede_eliminar'] ?? false,
                ]);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Permisos actualizados exitosamente');
    }
}
