<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionMqtt;
use Illuminate\Http\Request;

class ConfiguracionMqttController extends Controller
{
    public function index()
    {
        $catName = 'configuracion';
        $empresaId = auth()->user()->id_empresa;
        $configuracion = ConfiguracionMqtt::where('id_empresa', $empresaId)->first();
        
        return view('admin.mqtt.configuracion', compact('configuracion', 'catName'));
    }

    public function edit()
    {
        $catName = 'configuracion';
        $empresaId = auth()->user()->id_empresa;
        $configuracion = ConfiguracionMqtt::where('id_empresa', $empresaId)->firstOrCreate(
            ['id_empresa' => $empresaId],
            [
                'nombre' => 'Configuración MQTT',
                'host' => 'localhost',
                'puerto' => 1883,
                'usuario' => '',
                'contraseña' => '',
                'activa' => false,
            ]
        );
        
        return view('admin.mqtt.editar', compact('configuracion', 'catName'));
    }

    public function update(Request $request)
    {
        $empresaId = auth()->user()->id_empresa;
        
        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'host' => 'required|string',
            'puerto' => 'required|integer|min:1|max:65535',
            'usuario' => 'nullable|string',
            'contraseña' => 'nullable|string',
            'activa' => 'boolean',
        ]);

        $configuracion = ConfiguracionMqtt::where('id_empresa', $empresaId)->first();
        
        if (!$configuracion) {
            $configuracion = new ConfiguracionMqtt();
            $configuracion->id_empresa = $empresaId;
        }

        $configuracion->fill($request->all());
        $configuracion->activa = $request->has('activa');
        $configuracion->save();

        return redirect()->route('mqtt.configuracion')
            ->with('success', 'Configuración MQTT actualizada correctamente');
    }

    public function testConnection()
    {
        $empresaId = auth()->user()->id_empresa;
        $configuracion = ConfiguracionMqtt::where('id_empresa', $empresaId)->first();
        
        if (!$configuracion) {
            return back()->with('error', 'No hay configuración MQTT');
        }

        try {
            // Aquí se implementaría la lógica de conexión real a MQTT
            // Por ahora es un placeholder
            return back()->with('success', 'Conexión MQTT exitosa (placeholder)');
        } catch (\Exception $e) {
            return back()->with('error', 'Error en conexión MQTT: ' . $e->getMessage());
        }
    }
}
