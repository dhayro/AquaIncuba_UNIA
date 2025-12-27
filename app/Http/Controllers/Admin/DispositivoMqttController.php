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
}
