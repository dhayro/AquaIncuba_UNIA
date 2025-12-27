<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    /**
     * Listar sensores
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        $sensores = Sensor::where('id_empresa', $empresaId)
            ->paginate(15);

        return view('admin.sensores.index', [
            'sensores' => $sensores,
            'title' => 'Gestión de Sensores',
            'catName' => 'sensores',
        ]);
    }

    /**
     * Crear sensor
     */
    public function create()
    {
        return view('admin.sensores.create', [
            'title' => 'Crear Sensor',
            'catName' => 'sensores',
        ]);
    }

    /**
     * Guardar nuevo sensor
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sensores,codigo',
            'tipo' => 'required|string|in:temperatura,ph,oxigeno_disuelto,turbidez,conductividad',
            'unidad_medida' => 'required|string|max:20',
            'rango_minimo' => 'nullable|numeric',
            'rango_maximo' => 'nullable|numeric',
            'factor_calibracion' => 'nullable|numeric|default:1',
            'descripcion' => 'nullable|string',
        ]);

        $validated['id_empresa'] = auth()->user()->id_empresa;

        Sensor::create($validated);

        return redirect()->route('sensores.index')->with('success', 'Sensor creado exitosamente');
    }

    /**
     * Editar sensor
     */
    public function edit(Sensor $sensor)
    {
        if ($sensor->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.sensores.edit', [
            'sensor' => $sensor,
            'title' => 'Editar Sensor',
            'catName' => 'sensores',
        ]);
    }

    /**
     * Actualizar sensor
     */
    public function update(Request $request, Sensor $sensor)
    {
        if ($sensor->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:sensores,codigo,' . $sensor->id,
            'tipo' => 'required|string|in:temperatura,ph,oxigeno_disuelto,turbidez,conductividad',
            'unidad_medida' => 'required|string|max:20',
            'rango_minimo' => 'nullable|numeric',
            'rango_maximo' => 'nullable|numeric',
            'factor_calibracion' => 'nullable|numeric|default:1',
            'descripcion' => 'nullable|string',
        ]);

        $sensor->update($validated);

        return redirect()->route('sensores.index')->with('success', 'Sensor actualizado exitosamente');
    }

    /**
     * Eliminar sensor
     */
    public function destroy(Sensor $sensor)
    {
        if ($sensor->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $sensor->incubadoras()->detach();
        $sensor->delete();

        return redirect()->route('sensores.index')->with('success', 'Sensor eliminado exitosamente');
    }
}
