<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TemaMqtt;
use App\Models\DispositivoMqtt;
use Illuminate\Http\Request;

class TemaMqttController extends Controller
{
    /**
     * Mostrar listado de temas MQTT
     */
    public function index()
    {
        $catName = 'configuracion';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener temas MQTT de la empresa
        $temas = TemaMqtt::where('id_empresa', $empresaId)->paginate(15);

        return view('admin.temas-mqtt.index', compact('temas', 'catName'));
    }

    /**
     * Mostrar formulario para crear tema MQTT
     */
    public function create()
    {
        $catName = 'configuracion';
        return view('admin.temas-mqtt.create', compact('catName'));
    }

    /**
     * Guardar nuevo tema MQTT
     */
    public function store(Request $request)
    {
        $request->validate([
            'tema' => 'required|string|min:3|max:255',
            'descripcion' => 'nullable|string|max:500',
            'tipo_dato' => 'required|string|max:50',
            'unidad' => 'nullable|string|max:50',
            'valor_minimo' => 'nullable|numeric',
            'valor_maximo' => 'nullable|numeric',
        ]);

        $empresaId = auth()->user()->id_empresa;

        TemaMqtt::create([
            'id_empresa' => $empresaId,
            'tema' => $request->tema,
            'descripcion' => $request->descripcion,
            'tipo_dato' => $request->tipo_dato,
            'unidad' => $request->unidad,
            'valor_minimo' => $request->valor_minimo,
            'valor_maximo' => $request->valor_maximo,
            'esta_activo' => true,
        ]);

        return redirect()->route('temas-mqtt.index')
            ->with('success', 'Tema MQTT creado exitosamente.');
    }

    /**
     * Mostrar detalles del tema MQTT
     */
    public function show(TemaMqtt $temaMqtt)
    {
        $catName = 'configuracion';
        // Verificar que el tema pertenece a la empresa del usuario
        $this->authorize($temaMqtt);

        return view('admin.temas-mqtt.show', compact('temaMqtt', 'catName'));
    }

    /**
     * Mostrar formulario para editar tema MQTT
     */
    public function edit(TemaMqtt $temaMqtt)
    {
        $catName = 'configuracion';
        // Verificar que el tema pertenece a la empresa del usuario
        $this->authorize($temaMqtt);

        return view('admin.temas-mqtt.edit', compact('temaMqtt', 'catName'));
    }

    /**
     * Actualizar tema MQTT
     */
    public function update(Request $request, TemaMqtt $temaMqtt)
    {
        // Verificar que el tema pertenece a la empresa del usuario
        $this->authorize($temaMqtt);

        $request->validate([
            'tema' => 'required|string|min:3|max:255|unique:temas_mqtt,tema,' . $temaMqtt->id,
            'descripcion' => 'nullable|string|max:500',
            'tipo_dato' => 'required|string|max:50',
            'unidad' => 'nullable|string|max:50',
            'valor_minimo' => 'nullable|numeric',
            'valor_maximo' => 'nullable|numeric',
            'esta_activo' => 'boolean',
        ]);

        $temaMqtt->update([
            'tema' => $request->tema,
            'descripcion' => $request->descripcion,
            'tipo_dato' => $request->tipo_dato,
            'unidad' => $request->unidad,
            'valor_minimo' => $request->valor_minimo,
            'valor_maximo' => $request->valor_maximo,
            'esta_activo' => $request->boolean('esta_activo', false),
        ]);

        return redirect()->route('temas-mqtt.index')
            ->with('success', 'Tema MQTT actualizado exitosamente.');
    }

    /**
     * Eliminar tema MQTT
     */
    public function destroy(TemaMqtt $temaMqtt)
    {
        // Verificar que el tema pertenece a la empresa del usuario
        $this->authorize($temaMqtt);

        $temaMqtt->delete();

        return redirect()->route('temas-mqtt.index')
            ->with('success', 'Tema MQTT eliminado exitosamente.');
    }

    /**
     * Helper para autorización
     */
    private function authorize(TemaMqtt $tema)
    {
        $empresaId = auth()->user()->id_empresa;
        
        if ($tema->id_empresa !== $empresaId) {
            abort(403);
        }
    }
}
