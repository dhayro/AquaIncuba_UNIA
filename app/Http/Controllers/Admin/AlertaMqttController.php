<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AlertaMqtt;
use App\Models\Sensor;
use Illuminate\Http\Request;

class AlertaMqttController extends Controller
{
    public function index()
    {
        $catName = 'monitoreo';
        $empresaId = auth()->user()->id_empresa;
        
        $alertas = AlertaMqtt::whereHas('incubadora', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })
            ->with('sensor', 'incubadora')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.alertas.index', compact('alertas', 'catName'));
    }

    public function create()
    {
        $catName = 'monitoreo';
        $sensores = Sensor::all();
        return view('admin.alertas.create', compact('sensores', 'catName'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_sensor' => 'required|exists:sensores,id',
            'id_incubadora' => 'required|exists:incubadoras,id',
            'nombre_sensor' => 'required|string|max:100',
            'valor_umbral' => 'required|numeric',
            'tipo_alerta' => 'required|in:fuera_de_rango,critica,advertencia',
            'severidad' => 'required|in:baja,media,alta,critica',
            'mensaje' => 'required|string',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Verificar que la incubadora pertenece a la empresa
        $incubadora = \App\Models\Incubadora::findOrFail($request->id_incubadora);
        if ($incubadora->id_empresa !== $empresaId) {
            abort(403);
        }

        AlertaMqtt::create($request->all());

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta creada correctamente');
    }

    public function edit(AlertaMqtt $alerta)
    {
        $catName = 'monitoreo';
        // Verificar autorización
        $this->authorize($alerta);

        $sensores = \App\Models\Sensor::all();
        $incubadoras = \App\Models\Incubadora::where('id_empresa', auth()->user()->id_empresa)->get();
        return view('admin.alertas.edit', compact('alerta', 'sensores', 'incubadoras', 'catName'));
    }

    public function update(Request $request, AlertaMqtt $alerta)
    {
        // Verificar autorización
        $this->authorize($alerta);

        $request->validate([
            'id_sensor' => 'required|exists:sensores,id',
            'id_incubadora' => 'required|exists:incubadoras,id',
            'nombre_sensor' => 'required|string|max:100',
            'valor_umbral' => 'required|numeric',
            'tipo_alerta' => 'required|in:fuera_de_rango,critica,advertencia',
            'severidad' => 'required|in:baja,media,alta,critica',
            'mensaje' => 'required|string',
        ]);

        $alerta->update($request->all());

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta actualizada correctamente');
    }

    public function destroy(AlertaMqtt $alerta)
    {
        // Verificar autorización
        $this->authorize($alerta);

        $alerta->delete();

        return redirect()->route('alertas.index')
            ->with('success', 'Alerta eliminada correctamente');
    }

    /**
     * Autorizar acceso a alerta
     */
    private function authorize(AlertaMqtt $alerta)
    {
        if ($alerta->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
