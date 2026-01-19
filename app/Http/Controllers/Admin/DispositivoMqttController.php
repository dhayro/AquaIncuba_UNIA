<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DispositivoMqtt;
use App\Models\ConfiguracionMqtt;
use Illuminate\Http\Request;

class DispositivoMqttController extends Controller
{
    /**
     * Mostrar listado de dispositivos MQTT
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener dispositivos MQTT de la empresa
        $dispositivos = DispositivoMqtt::where('id_empresa', $empresaId)->paginate(15);

        return view('admin.dispositivos-mqtt.index', compact('dispositivos'), ['catName' => 'configuracion']);
    }

    /**
     * Obtener datos de dispositivos en formato JSON para DataTables
     */
    public function getDispositivosData()
    {
        try {
            $empresaId = auth()->user()->id_empresa;
            $dispositivos = DispositivoMqtt::where('id_empresa', $empresaId)->get();

            $data = $dispositivos->map(function ($dispositivo) {
                $estadoBadge = '<span class="badge ' . 
                              ($dispositivo->esta_activo ? 'bg-success' : 'bg-danger') . 
                              ' btn-toggle-dispositivo-estado" data-dispositivo-id="' . $dispositivo->id . '" style="cursor: pointer;" title="Click para cambiar estado">' . 
                              ($dispositivo->esta_activo ? 'Activo' : 'Inactivo') . '</span>';

                $acciones = $this->generateDispositivoActions($dispositivo->id, $dispositivo->nombre);

                return [
                    'nombre' => $dispositivo->nombre,
                    'id_dispositivo' => $dispositivo->id_dispositivo,
                    'tema_mqtt' => $dispositivo->tema_mqtt ?? 'N/A',
                    'estado' => $estadoBadge,
                    'acciones' => $acciones
                ];
            });

            return response()->json([
                'draw' => request('draw', 1),
                'recordsTotal' => $dispositivos->count(),
                'recordsFiltered' => $dispositivos->count(),
                'data' => $data->values()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getDispositivosData: ' . $e->getMessage());
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
     * Generar botones de acción para dispositivos
     */
    private function generateDispositivoActions($dispositivoId, $dispositivoNombre)
    {
        $viewBtn = '<button type="button" class="btn btn-sm btn-outline-primary btn-view-dispositivo" data-dispositivo-id="' . $dispositivoId . '" title="Ver detalles" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>';

        $editBtn = '<button type="button" class="btn btn-sm btn-outline-warning btn-edit-dispositivo ms-2" data-dispositivo-id="' . $dispositivoId . '" title="Editar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>';

        $deleteBtn = '<button type="button" class="btn btn-sm btn-outline-danger btn-delete-dispositivo ms-2" data-dispositivo-id="' . $dispositivoId . '" data-dispositivo-nombre="' . htmlspecialchars($dispositivoNombre) . '" title="Eliminar" style="padding: 0.375rem 0.75rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </button>';

        return $viewBtn . ' ' . $editBtn . ' ' . $deleteBtn;
    }

    /**
     * Mostrar formulario para crear dispositivo MQTT
     */
    public function create()
    {
        return view('admin.dispositivos-mqtt.create', ['catName' => 'configuracion']);
    }

    /**
     * Guardar nuevo dispositivo MQTT
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'id_dispositivo' => 'required|string|min:3|max:100|unique:dispositivos_mqtt',
            'tipo_dispositivo' => 'required|string|max:100',
            'tema_mqtt' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'esta_activo' => 'boolean',
        ]);

        $empresaId = auth()->user()->id_empresa;

        DispositivoMqtt::create([
            'id_empresa' => $empresaId,
            'nombre' => $request->nombre,
            'id_dispositivo' => $request->id_dispositivo,
            'tipo_dispositivo' => $request->tipo_dispositivo,
            'tema_mqtt' => $request->tema_mqtt,
            'ubicacion' => $request->ubicacion,
            'esta_activo' => $request->boolean('esta_activo', false),
        ]);

        // Return JSON if requested via AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Dispositivo MQTT creado correctamente']);
        }

        return redirect()->route('dispositivos-mqtt.index')
            ->with('success', 'Dispositivo MQTT creado exitosamente.');
    }

    /**
     * Mostrar detalles del dispositivo MQTT
     */
    public function show(DispositivoMqtt $dispositivoMqtt)
    {
        // Verificar que el dispositivo pertenece a la empresa del usuario
        if ($dispositivoMqtt->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.dispositivos-mqtt.show', compact('dispositivoMqtt'), ['catName' => 'configuracion']);
    }

    /**
     * Mostrar formulario para editar dispositivo MQTT
     */
    public function edit(DispositivoMqtt $dispositivoMqtt)
    {
        // Verificar que el dispositivo pertenece a la empresa del usuario
        if ($dispositivoMqtt->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        // Return JSON if requested via AJAX
        if (request()->expectsJson()) {
            return response()->json($dispositivoMqtt);
        }

        return view('admin.dispositivos-mqtt.edit', compact('dispositivoMqtt'), ['catName' => 'configuracion']);
    }

    /**
     * Actualizar dispositivo MQTT
     */
    public function update(Request $request, DispositivoMqtt $dispositivoMqtt)
    {
        // Verificar que el dispositivo pertenece a la empresa del usuario
        if ($dispositivoMqtt->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'id_dispositivo' => 'required|string|min:3|max:100|unique:dispositivos_mqtt,id_dispositivo,' . $dispositivoMqtt->id,
            'tipo_dispositivo' => 'required|string|max:100',
            'tema_mqtt' => 'required|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'esta_activo' => 'boolean',
        ]);

        $dispositivoMqtt->update([
            'nombre' => $request->nombre,
            'id_dispositivo' => $request->id_dispositivo,
            'tipo_dispositivo' => $request->tipo_dispositivo,
            'tema_mqtt' => $request->tema_mqtt,
            'ubicacion' => $request->ubicacion,
            'esta_activo' => $request->boolean('esta_activo', false),
        ]);

        // Return JSON if requested via AJAX
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Dispositivo MQTT actualizado correctamente']);
        }

        return redirect()->route('dispositivos-mqtt.index')
            ->with('success', 'Dispositivo MQTT actualizado exitosamente.');
    }

    /**
     * Eliminar dispositivo MQTT
     */
    public function destroy(DispositivoMqtt $dispositivoMqtt)
    {
        // Verificar que el dispositivo pertenece a la empresa del usuario
        if ($dispositivoMqtt->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $dispositivoMqtt->delete();

        // Return JSON if requested via AJAX
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Dispositivo MQTT eliminado correctamente']);
        }

        return redirect()->route('dispositivos-mqtt.index')
            ->with('success', 'Dispositivo MQTT eliminado exitosamente.');
    }

    /**
     * Helper para autorización
     */
    private function authorize(DispositivoMqtt $dispositivo, $empresaId)
    {
        if ($empresaId !== auth()->user()->id_empresa) {
            abort(403);
        }
    }

    /**
     * Toggle estado del dispositivo MQTT
     */
    public function toggleEstado(DispositivoMqtt $dispositivoMqtt)
    {
        // Verificar que el dispositivo pertenece a la empresa del usuario
        if ($dispositivoMqtt->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $dispositivoMqtt->esta_activo = !$dispositivoMqtt->esta_activo;
        $dispositivoMqtt->save();

        $estado = $dispositivoMqtt->esta_activo ? 'Activo' : 'Inactivo';

        return response()->json([
            'success' => true,
            'message' => "Dispositivo MQTT cambió a {$estado}",
            'esta_activo' => $dispositivoMqtt->esta_activo,
            'estado' => $estado
        ]);
    }
}
