<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatoCrudoEstudio;
use App\Models\EstudioCalidadAgua;
use Illuminate\Http\Request;

class DatoCrudoEstudioController extends Controller
{
    /**
     * Mostrar listado de datos crudos de estudio
     */
    public function index()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener datos crudos de estudios de la empresa
        $datos = DatoCrudoEstudio::whereHas('muestraEstudio.estudioCalidad', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })->with('muestraEstudio.estudioCalidad.incubadora', 'sensor')->paginate(20);

        return view('admin.datos-estudio.crudos.index', compact('datos', 'catName'));
    }

    /**
     * Mostrar formulario para cargar datos crudos
     */
    public function create()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener estudios en progreso o completados
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->whereIn('estado', ['en_progreso', 'completado'])
            ->with('incubadora')
            ->get();

        if ($estudios->isEmpty()) {
            return redirect()->route('estudios.index')
                ->with('warning', 'No hay estudios disponibles para cargar datos.');
        }

        return view('admin.datos-estudio.crudos.create', compact('estudios', 'catName'));
    }

    /**
     * Guardar nuevos datos crudos
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_estudio_calidad_agua' => 'required|exists:estudios_calidad_agua,id',
            'numero_muestra' => 'required|integer|min:1',
            'temperatura' => 'nullable|numeric|between:0,50',
            'ph' => 'nullable|numeric|between:0,14',
            'conductividad' => 'nullable|numeric|min:0',
            'oxigeno_disuelto' => 'nullable|numeric|min:0',
            'turbidez' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Verificar que el estudio pertenece a la empresa del usuario
        $estudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->findOrFail($request->id_estudio_calidad_agua);

        DatoCrudoEstudio::create([
            'id_estudio_calidad_agua' => $request->id_estudio_calidad_agua,
            'numero_muestra' => $request->numero_muestra,
            'temperatura' => $request->temperatura,
            'ph' => $request->ph,
            'conductividad' => $request->conductividad,
            'oxigeno_disuelto' => $request->oxigeno_disuelto,
            'turbidez' => $request->turbidez,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('datos-crudos.index')
            ->with('success', 'Datos crudos guardados exitosamente.');
    }

    /**
     * Mostrar detalles de dato crudo
     */
    public function show(DatoCrudoEstudio $datoCrudo)
    {
        $catName = 'estudios';
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoCrudo);

        return view('admin.datos-estudio.crudos.show', compact('datoCrudo', 'catName'));
    }

    /**
     * Mostrar formulario para editar dato crudo
     */
    public function edit(DatoCrudoEstudio $datoCrudo)
    {
        $catName = 'estudios';
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoCrudo);

        $empresaId = auth()->user()->id_empresa;
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->with('incubadora')
            ->get();

        return view('admin.datos-estudio.crudos.edit', compact('datoCrudo', 'estudios', 'catName'));
    }

    /**
     * Actualizar dato crudo
     */
    public function update(Request $request, DatoCrudoEstudio $datoCrudo)
    {
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoCrudo);

        $request->validate([
            'numero_muestra' => 'required|integer|min:1',
            'temperatura' => 'nullable|numeric|between:0,50',
            'ph' => 'nullable|numeric|between:0,14',
            'conductividad' => 'nullable|numeric|min:0',
            'oxigeno_disuelto' => 'nullable|numeric|min:0',
            'turbidez' => 'nullable|numeric|min:0',
            'observaciones' => 'nullable|string|max:1000',
        ]);

        $datoCrudo->update([
            'numero_muestra' => $request->numero_muestra,
            'temperatura' => $request->temperatura,
            'ph' => $request->ph,
            'conductividad' => $request->conductividad,
            'oxigeno_disuelto' => $request->oxigeno_disuelto,
            'turbidez' => $request->turbidez,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()->route('datos-crudos.index')
            ->with('success', 'Datos crudos actualizados exitosamente.');
    }

    /**
     * Eliminar dato crudo
     */
    public function destroy(DatoCrudoEstudio $datoCrudo)
    {
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoCrudo);

        $datoCrudo->delete();

        return redirect()->route('datos-crudos.index')
            ->with('success', 'Datos crudos eliminados exitosamente.');
    }

    /**
     * Helper para autorización
     */
    private function authorize(DatoCrudoEstudio $dato)
    {
        if ($dato->muestraEstudio->estudioCalidad->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
