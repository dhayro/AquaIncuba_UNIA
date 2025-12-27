<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParametroEstudio;
use Illuminate\Http\Request;

class ParametroEstudioController extends Controller
{
    /**
     * Mostrar listado de parámetros de estudio
     */
    public function index()
    {
        $catName = 'parametros';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener parámetros de la empresa
        $parametros = ParametroEstudio::where('id_empresa', $empresaId)
            ->paginate(20);

        return view('admin.parametros-estudio.index', compact('parametros', 'catName'));
    }

    /**
     * Mostrar formulario para crear parámetro
     */
    public function create()
    {
        $catName = 'parametros';
        return view('admin.parametros-estudio.create', compact('catName'));
    }

    /**
     * Guardar nuevo parámetro
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|min:2|max:50|unique:parametros_estudio,codigo',
            'nombre' => 'required|string|min:3|max:100',
            'unidad' => 'required|string|max:50',
            'tipo_medicion' => 'required|in:ambas,temperatura,ph',
            'minimo_optimo' => 'nullable|numeric',
            'maximo_optimo' => 'nullable|numeric',
            'minimo_critico' => 'nullable|numeric',
            'maximo_critico' => 'nullable|numeric',
            'decimales' => 'nullable|integer|min:0|max:10',
        ]);

        $empresaId = auth()->user()->id_empresa;

        ParametroEstudio::create([
            'id_empresa' => $empresaId,
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'unidad' => $request->unidad,
            'tipo_medicion' => $request->tipo_medicion,
            'minimo_optimo' => $request->minimo_optimo,
            'maximo_optimo' => $request->maximo_optimo,
            'minimo_critico' => $request->minimo_critico,
            'maximo_critico' => $request->maximo_critico,
            'decimales' => $request->decimales ?? 2,
        ]);

        return redirect()->route('parametros.index')
            ->with('success', 'Parámetro creado exitosamente.');
    }

    /**
     * Mostrar detalles de parámetro
     */
    public function show(ParametroEstudio $parametro)
    {
        $catName = 'parametros';
        // Verificar que el parámetro pertenece a la empresa del usuario
        if ($parametro->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.parametros-estudio.show', compact('parametro', 'catName'));
    }

    /**
     * Mostrar formulario para editar parámetro
     */
    public function edit(ParametroEstudio $parametro)
    {
        $catName = 'parametros';
        // Verificar que el parámetro pertenece a la empresa del usuario
        if ($parametro->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        return view('admin.parametros-estudio.edit', compact('parametro', 'catName'));
    }

    /**
     * Actualizar parámetro
     */
    public function update(Request $request, ParametroEstudio $parametro)
    {
        // Verificar que el parámetro pertenece a la empresa del usuario
        if ($parametro->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'unidad' => 'required|string|max:50',
            'tipo_medicion' => 'required|in:ambas,temperatura,ph',
            'minimo_optimo' => 'nullable|numeric',
            'maximo_optimo' => 'nullable|numeric',
            'minimo_critico' => 'nullable|numeric',
            'maximo_critico' => 'nullable|numeric',
            'decimales' => 'nullable|integer|min:0|max:10',
        ]);

        $parametro->update([
            'nombre' => $request->nombre,
            'unidad' => $request->unidad,
            'tipo_medicion' => $request->tipo_medicion,
            'minimo_optimo' => $request->minimo_optimo,
            'maximo_optimo' => $request->maximo_optimo,
            'minimo_critico' => $request->minimo_critico,
            'maximo_critico' => $request->maximo_critico,
            'decimales' => $request->decimales ?? 2,
        ]);

        return redirect()->route('parametros.index')
            ->with('success', 'Parámetro actualizado exitosamente.');
    }

    /**
     * Eliminar parámetro
     */
    public function destroy(ParametroEstudio $parametro)
    {
        // Verificar que el parámetro pertenece a la empresa del usuario
        if ($parametro->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }

        $parametro->delete();

        return redirect()->route('parametros.index')
            ->with('success', 'Parámetro eliminado exitosamente.');
    }
}
