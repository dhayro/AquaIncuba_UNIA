<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;
use App\Models\RolUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Listar usuarios
     */
    public function index()
    {
        return view('admin.usuarios.index', [
            'title' => 'Gestión de Usuarios',
            'catName' => 'usuarios',
        ]);
    }

    /**
     * Obtener datos de usuarios en formato JSON para DataTables
     */
    public function getUsuariosData()
    {
        try {
            $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
            
            $query = Usuario::query();
            if ($empresaId) {
                $query->where('id_empresa', $empresaId);
            }
            $usuarios = $query->get();
            
            $data = [];
            foreach ($usuarios as $usuario) {
                $rowData = $this->generateUsuarioRow($usuario);
                
                $data[] = [
                    $rowData['nombre'],
                    $rowData['correo'],
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
            \Log::error('Error en getUsuariosData: ' . $e->getMessage());
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
     * Generar fila HTML para un usuario
     */
    private function generateUsuarioRow($usuario)
    {
        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-usuario" data-usuario-id="' . $usuario->id . '" data-bs-toggle="modal" data-bs-target="#editUsuarioModal" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';
        
        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-usuario ms-2" data-usuario-id="' . $usuario->id . '" data-bs-toggle="modal" data-bs-target="#deleteUsuarioModal" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';

        return [
            'nombre' => '<span class="fw-bold">' . ucfirst($usuario->nombre) . '</span>',
            'correo' => $usuario->correo ?? '<span class="text-muted">—</span>',
            'acciones' => $editBtn . ' ' . $deleteBtn
        ];
    }

    /**
     * Crear usuario
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo',
            'contraseña' => 'required|string|min:8',
        ]);

        try {
            $validated['id_empresa'] = auth()->user()->id_empresa;
            $validated['contraseña'] = Hash::make($validated['contraseña']);

            $usuario = Usuario::create($validated);

            if ($request->expectsJson()) {
                $rowData = $this->generateUsuarioRow($usuario);
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado correctamente',
                    'usuario' => $usuario,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el usuario: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al crear el usuario');
        }
    }

    /**
     * Editar usuario
     */
    public function edit($id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        
        if ($empresaId && $usuario->id_empresa !== $empresaId) {
            return response()->json(['error' => 'No tienes permiso para editar este usuario'], 403);
        }
        
        return response()->json([
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'correo' => $usuario->correo
        ]);
    }

    /**
     * Actualizar usuario
     */
    public function update(Request $request, $id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }
            return back()->with('error', 'Usuario no encontrado');
        }
        
        if ($empresaId && $usuario->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuarios,correo,' . $usuario->id,
            'contraseña' => 'nullable|string|min:8',
        ]);

        try {
            if (!empty($validated['contraseña'])) {
                $validated['contraseña'] = Hash::make($validated['contraseña']);
            } else {
                unset($validated['contraseña']);
            }

            $usuario->update($validated);

            if ($request->expectsJson()) {
                $rowData = $this->generateUsuarioRow($usuario);
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario actualizado correctamente',
                    'usuario' => $usuario,
                    'row' => $rowData
                ]);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al actualizar el usuario');
        }
    }

    /**
     * Eliminar usuario
     */
    public function destroy(Request $request, $id)
    {
        $empresaId = auth()->user() ? auth()->user()->id_empresa : null;
        $usuario = Usuario::find($id);
        
        if (!$usuario) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Usuario no encontrado'], 404);
            }
            return back()->with('error', 'Usuario no encontrado');
        }
        
        if ($empresaId && $usuario->id_empresa !== $empresaId) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No tienes permiso'], 403);
            }
            return back()->with('error', 'No tienes permiso');
        }
        
        try {
            // Eliminar roles asociados
            RolUsuario::where('id_usuario', $usuario->id)->delete();
            
            $usuario->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado correctamente'
                ]);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
                ], 400);
            }
            return back()->with('error', 'Error al eliminar el usuario');
        }
    }
}

