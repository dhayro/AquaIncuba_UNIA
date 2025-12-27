<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incubadora;
use Illuminate\Http\Request;

class IncubadoraController extends Controller
{
    /**
     * Listar incubadoras
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        $incubadoras = Incubadora::where('id_empresa', $empresaId)
            ->with(['sensores'])
            ->paginate(15);

        return view('admin.incubadoras.index', [
            'incubadoras' => $incubadoras,
            'title' => 'Gestión de Incubadoras',
            'catName' => 'incubadoras',
        ]);
    }

    /**
     * Crear incubadora
     */
    public function create()
    {
        return view('admin.incubadoras.create', [
            'title' => 'Crear Incubadora',
            'catName' => 'incubadoras',
        ]);
    }

    /**
     * Guardar nueva incubadora
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:incubadoras,codigo',
            'descripcion' => 'nullable|string',
            'volumen_litros' => 'nullable|numeric|min:0.1',
            'temperatura_optima' => 'nullable|numeric',
            'ph_optimo' => 'nullable|numeric',
            'oxigeno_disuelto_optimo' => 'nullable|numeric',
        ]);

        $validated['id_empresa'] = auth()->user()->id_empresa;

        Incubadora::create($validated);

        return redirect()->route('incubadoras.index')->with('success', 'Incubadora creada exitosamente');
    }

    /**
     * Editar incubadora
     */
    public function edit(Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.incubadoras.edit', [
            'incubadora' => $incubadora,
            'title' => 'Editar Incubadora',
            'catName' => 'incubadoras',
        ]);
    }

    /**
     * Actualizar incubadora
     */
    public function update(Request $request, Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|unique:incubadoras,codigo,' . $incubadora->id,
            'descripcion' => 'nullable|string',
            'volumen_litros' => 'nullable|numeric|min:0.1',
            'temperatura_optima' => 'nullable|numeric',
            'ph_optimo' => 'nullable|numeric',
            'oxigeno_disuelto_optimo' => 'nullable|numeric',
        ]);

        $incubadora->update($validated);

        return redirect()->route('incubadoras.index')->with('success', 'Incubadora actualizada exitosamente');
    }

    /**
     * Eliminar incubadora
     */
    public function destroy(Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $incubadora->sensoresIncubadora()->delete();
        $incubadora->delete();

        return redirect()->route('incubadoras.index')->with('success', 'Incubadora eliminada exitosamente');
    }

    /**
     * Asignar sensores a incubadora
     */
    public function asignarSensores(Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $sensores = \App\Models\Sensor::where('id_empresa', auth()->user()->id_empresa)
            ->get();
        
        $sensoresAsignados = $incubadora->sensores()->pluck('id_sensor')->toArray();

        return view('admin.incubadoras.sensores', [
            'incubadora' => $incubadora,
            'sensores' => $sensores,
            'sensoresAsignados' => $sensoresAsignados,
            'title' => 'Asignar Sensores - ' . $incubadora->nombre,
            'catName' => 'incubadoras',
        ]);
    }

    /**
     * Guardar sensores asignados
     */
    public function guardarSensores(Request $request, Incubadora $incubadora)
    {
        if ($incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $validated = $request->validate([
            'sensores' => 'nullable|array',
            'sensores.*' => 'exists:sensores,id',
        ]);

        $incubadora->sensores()->sync($validated['sensores'] ?? []);

        return redirect()->route('incubadoras.index')->with('success', 'Sensores asignados exitosamente');
    }
}
