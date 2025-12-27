<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario as User;
use App\Models\Menu;
use App\Models\Rol;
use App\Models\RolUsuario;
use App\Models\PermisoMenuRol;
use Illuminate\Support\Facades\DB;

class MenuPermissionController extends Controller
{
    /**
     * Mostrar administrador de menús y permisos
     */
    public function index()
    {
        $usuarios = User::with(['roles' => function ($query) {
            $query->where('roles.id_empresa', auth()->user()->id_empresa);
        }])->where('usuarios.id_empresa', auth()->user()->id_empresa)->get();

        $roles = Rol::where('id_empresa', auth()->user()->id_empresa)->get();
        
        // Obtener menús para los selects en los modales
        $menus = Menu::where('id_empresa', auth()->user()->id_empresa)
            ->orderBy('nivel', 'asc')
            ->orderBy('orden', 'asc')
            ->get();

        return view('admin.menu-permissions.index', [
            'usuarios' => $usuarios,
            'roles' => $roles,
            'menus' => $menus,
        ]);
    }

    /**
     * Mostrar permisos de un rol específico
     */
    public function showRolePermissions($roleId)
    {
        $role = Rol::findOrFail($roleId);
        $menus = Menu::where('id_empresa', auth()->user()->id_empresa)
            ->orderBy('nivel', 'asc')
            ->orderBy('orden', 'asc')
            ->get();

        $permissions = PermisoMenuRol::where('id_rol', $roleId)
            ->pluck('id_menu')
            ->toArray();

        return view('admin.menu-permissions.role-permissions', [
            'role' => $role,
            'menus' => $menus,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Actualizar permisos de un rol
     */
    public function updateRolePermissions($roleId)
    {
        $role = Rol::findOrFail($roleId);
        $validated = request()->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:menus,id',
        ]);

        // Limpiar permisos antiguos
        PermisoMenuRol::where('id_rol', $roleId)->delete();

        // Agregar nuevos permisos
        foreach ($validated['permissions'] ?? [] as $menuId) {
            PermisoMenuRol::create([
                'id_rol' => $roleId,
                'id_menu' => $menuId,
                'puede_ver' => 1,
                'puede_crear' => request('puede_crear_' . $menuId, 0),
                'puede_editar' => request('puede_editar_' . $menuId, 0),
                'puede_eliminar' => request('puede_eliminar_' . $menuId, 0),
            ]);
        }

        return redirect()->route('menu-permissions.index')
            ->with('success', 'Permisos actualizados correctamente');
    }

    /**
     * Mostrar permisos de un usuario
     */
    public function showUserPermissions($userId)
    {
        $user = User::with(['roles' => function ($query) {
            $query->where('roles.id_empresa', auth()->user()->id_empresa);
        }])->findOrFail($userId);
        $allRoles = Rol::where('id_empresa', auth()->user()->id_empresa)->get();
        $userRoles = $user->roles()->pluck('id_rol')->toArray();

        return view('admin.menu-permissions.user-roles', [
            'user' => $user,
            'allRoles' => $allRoles,
            'userRoles' => $userRoles,
        ]);
    }

    /**
     * Actualizar roles de un usuario
     */
    public function updateUserRoles($userId)
    {
        $user = User::findOrFail($userId);
        $validated = request()->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Limpiar roles antiguos
        RolUsuario::where('id_usuario', $userId)->delete();

        // Agregar nuevos roles
        foreach ($validated['roles'] ?? [] as $roleId) {
            RolUsuario::create([
                'id_usuario' => $userId,
                'id_rol' => $roleId,
                'id_empresa' => auth()->user()->id_empresa,
            ]);
        }

        return redirect()->route('menu-permissions.index')
            ->with('success', 'Roles del usuario actualizados correctamente');
    }

    /**
     * Generar HTML de fila de tabla para un menú
     */
    private function generateMenuRow($menu, $parentName = '—')
    {
        $level = $menu->nivel === 0 ? 'menu-level-0' : 'menu-level-1';
        $badge = $menu->nivel === 0 ? '<span class="badge bg-info">Grupo</span>' : '<span class="badge bg-secondary">Submenu</span>';
        $url = $menu->url ?? '—';
        $orden = $menu->orden;
        $buttons = $this->generateActionButtons($menu->id);
        
        return [
            'nombre' => '<span class="' . $level . '">' . $menu->nombre . '</span>',
            'url' => '<code>' . $url . '</code>',
            'tipo' => $badge,
            'padre' => $parentName,
            'orden' => '<span class="badge bg-light text-dark">' . $orden . '</span>',
            'acciones' => $buttons
        ];
    }

    /**
     * Renderizar fila de menú como HTML <tr>
     */
    private function renderMenuRow($rowData)
    {
        return '<tr>
                    <td>' . $rowData['nombre'] . '</td>
                    <td>' . $rowData['url'] . '</td>
                    <td>' . $rowData['tipo'] . '</td>
                    <td><small class="text-muted">' . $rowData['padre'] . '</small></td>
                    <td>' . $rowData['orden'] . '</td>
                    <td>' . $rowData['acciones'] . '</td>
                </tr>';
    }

    /**
     * Generar HTML de botones de acción para una fila
     */
    private function generateActionButtons($menuId)
    {
        return '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-menu" data-menu-id="' . $menuId . '" data-bs-toggle="modal" data-bs-target="#editMenuModal" title="Editar" style="padding: 0.375rem 0.75rem;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button><button type="button" class="btn btn-sm btn-outline-danger btn-delete-menu ms-2" data-menu-id="' . $menuId . '" data-bs-toggle="modal" data-bs-target="#deleteMenuModal" title="Eliminar" style="padding: 0.375rem 0.75rem;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></button>';
    }

    /**
     * Almacenar nuevo menú
     */
    public function storeMenu()
    {
        $validated = request()->validate([
            'nombre' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icono' => 'nullable|string|max:255',
            'id_padre' => 'nullable|exists:menus,id',
            'orden' => 'required|integer|min:1',
        ]);

        try {
            $empresaId = auth()->user()->id_empresa;

            // Determinar el nivel
            $nivel = empty($validated['id_padre']) ? 0 : 1;

            $menu = Menu::create([
                'nombre' => $validated['nombre'],
                'url' => $validated['url'],
                'icono' => $validated['icono'] ?? 'feather feather-menu',
                'id_padre' => $validated['id_padre'],
                'nivel' => $nivel,
                'orden' => $validated['orden'],
                'id_empresa' => $empresaId,
            ]);

            if (request()->expectsJson()) {
                // Obtener nombre del padre
                $parentName = '—';
                if (!empty($validated['id_padre'])) {
                    $parent = Menu::find($validated['id_padre']);
                    $parentName = $parent ? $parent->nombre : '—';
                }
                
                $rowData = $this->generateMenuRow($menu, $parentName);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Menú creado correctamente',
                    'menu' => $menu,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('menu-permissions.index')
                ->with('success', 'Menú creado correctamente');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el menú: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear el menú');
        }
    }

    /**
     * Obtener menú por ID (para AJAX)
     */
    public function getMenu($menuId)
    {
        $menu = Menu::where('id_empresa', auth()->user()->id_empresa)
            ->findOrFail($menuId);

        return response()->json($menu);
    }

    /**
     * Actualizar menú
     */
    public function updateMenu($menuId)
    {
        $menu = Menu::where('id_empresa', auth()->user()->id_empresa)
            ->findOrFail($menuId);

        $validated = request()->validate([
            'nombre' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'icono' => 'nullable|string|max:255',
            'id_padre' => 'nullable|exists:menus,id',
            'orden' => 'required|integer|min:1',
        ]);

        try {
            // Determinar el nivel basado en id_padre
            $nivel = empty($validated['id_padre']) ? 0 : 1;

            $menu->update([
                'nombre' => $validated['nombre'],
                'url' => $validated['url'],
                'icono' => $validated['icono'] ?? $menu->icono,
                'id_padre' => $validated['id_padre'],
                'nivel' => $nivel,
                'orden' => $validated['orden'],
            ]);

            if (request()->expectsJson()) {
                // Obtener nombre del padre
                $parentName = '—';
                if (!empty($validated['id_padre'])) {
                    $parent = Menu::find($validated['id_padre']);
                    $parentName = $parent ? $parent->nombre : '—';
                }
                
                $rowData = $this->generateMenuRow($menu, $parentName);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Menú actualizado correctamente',
                    'menu' => $menu,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('menu-permissions.index')
                ->with('success', 'Menú actualizado correctamente');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el menú: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar el menú');
        }
    }

    /**
     * Obtener datos de menús en formato JSON para DataTables
     */
    public function getMenusData()
    {
        try {
            $menus = Menu::where('id_empresa', auth()->user()->id_empresa)
                ->orderBy('nivel', 'asc')
                ->orderBy('orden', 'asc')
                ->get();

            // Construir array de datos para DataTables
            $data = [];
            $mainMenus = $menus->where('nivel', 0)->sortBy('orden');

            foreach ($mainMenus as $group) {
                $parentName = '—';
                $rowData = $this->generateMenuRow($group, $parentName);
                
                // DataTables espera un array con los valores de las celdas
                $data[] = [
                    $rowData['nombre'],
                    $rowData['url'],
                    $rowData['tipo'],
                    $rowData['padre'],
                    $rowData['orden'],
                    $rowData['acciones'],
                    $group->id  // Columna oculta para identificar la fila
                ];

                // Agregar submenús
                $groupSubmenus = $menus->where('id_padre', $group->id)->sortBy('orden');
                foreach ($groupSubmenus as $submenu) {
                    $subRowData = $this->generateMenuRow($submenu, $group->nombre);
                    $data[] = [
                        $subRowData['nombre'],
                        $subRowData['url'],
                        $subRowData['tipo'],
                        $subRowData['padre'],
                        $subRowData['orden'],
                        $subRowData['acciones'],
                        $submenu->id
                    ];
                }
            }

            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => count($data),
                'recordsFiltered' => count($data),
                'data' => $data
            ]);
        } catch (\Exception $e) {
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
     * Eliminar menú
     */
    public function destroyMenu($menuId)
    {
        $menu = Menu::where('id_empresa', auth()->user()->id_empresa)
            ->findOrFail($menuId);

        try {
            // Eliminar submenús si los hay
            Menu::where('id_padre', $menuId)->delete();

            // Eliminar permisos asociados
            PermisoMenuRol::where('id_menu', $menuId)->delete();

            $menu->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Menú eliminado correctamente'
                ]);
            }

            return redirect()->route('menu-permissions.index')
                ->with('success', 'Menú eliminado correctamente');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el menú: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar el menú');
        }
    }
}
