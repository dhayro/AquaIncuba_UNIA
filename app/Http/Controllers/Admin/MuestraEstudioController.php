<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MuestraEstudio;
use App\Models\EstudioCalidadAgua;
use Illuminate\Http\Request;

class MuestraEstudioController extends Controller
{
    /**
     * Mostrar listado de muestras de estudio
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener muestras de estudios de la empresa
        $muestras = MuestraEstudio::whereHas('estudio', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })->with('estudio.incubadora')->paginate(20);

        return view('admin.muestras-estudio.index', compact('muestras'), ['catName' => 'estudios']);
    }

    /**
     * Mostrar formulario para crear muestra
     */
    public function create()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener estudios en progreso
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->where('estado', 'en_progreso')
            ->with('incubadora')
            ->get();

        if ($estudios->isEmpty()) {
            return redirect()->route('estudios.index')
                ->with('warning', 'No hay estudios en progreso para agregar muestras.');
        }

        return view('admin.muestras-estudio.create', compact('estudios', 'catName'));
    }

    /**
     * Guardar nueva muestra
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_estudio_calidad_agua' => 'required|exists:estudios_calidad_agua,id',
            'numero_muestra' => 'required|integer|min:1|unique:muestras_estudios,numero_muestra',
            'fecha_recoleccion' => 'required|date',
            'hora_recoleccion' => 'nullable|date_format:H:i',
            'ubicacion_recoleccion' => 'nullable|string|max:255',
            'condiciones_ambientales' => 'nullable|string|max:500',
            'estado_muestra' => 'required|in:recibida,procesando,completada,descartada',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Verificar que el estudio pertenece a la empresa del usuario
        $estudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->findOrFail($request->id_estudio_calidad_agua);

        MuestraEstudio::create([
            'id_estudio_calidad_agua' => $request->id_estudio_calidad_agua,
            'numero_muestra' => $request->numero_muestra,
            'fecha_recoleccion' => $request->fecha_recoleccion,
            'hora_recoleccion' => $request->hora_recoleccion,
            'ubicacion_recoleccion' => $request->ubicacion_recoleccion,
            'condiciones_ambientales' => $request->condiciones_ambientales,
            'estado_muestra' => $request->estado_muestra,
        ]);

        return redirect()->route('muestras.index')
            ->with('success', 'Muestra creada exitosamente.');
    }

    /**
     * Mostrar detalles de muestra
     */
    public function show(MuestraEstudio $muestra)
    {
        $catName = 'estudios';
        // Verificar que la muestra pertenece a la empresa del usuario
        $this->authorize($muestra);

        return view('admin.muestras-estudio.show', compact('muestra', 'catName'));
    }

    /**
     * Mostrar formulario para editar muestra
     */
    public function edit(MuestraEstudio $muestra)
    {
        $catName = 'estudios';
        // Verificar que la muestra pertenece a la empresa del usuario
        $this->authorize($muestra);

        $empresaId = auth()->user()->id_empresa;
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->with('incubadora')
            ->get();

        return view('admin.muestras-estudio.edit', compact('muestra', 'estudios', 'catName'));
    }

    /**
     * Actualizar muestra
     */
    public function update(Request $request, MuestraEstudio $muestra)
    {
        // Verificar que la muestra pertenece a la empresa del usuario
        $this->authorize($muestra);

        $request->validate([
            'numero_muestra' => 'required|integer|min:1|unique:muestras_estudios,numero_muestra,' . $muestra->id,
            'fecha_recoleccion' => 'required|date',
            'hora_recoleccion' => 'nullable|date_format:H:i',
            'ubicacion_recoleccion' => 'nullable|string|max:255',
            'condiciones_ambientales' => 'nullable|string|max:500',
            'estado_muestra' => 'required|in:recibida,procesando,completada,descartada',
        ]);

        $muestra->update([
            'numero_muestra' => $request->numero_muestra,
            'fecha_recoleccion' => $request->fecha_recoleccion,
            'hora_recoleccion' => $request->hora_recoleccion,
            'ubicacion_recoleccion' => $request->ubicacion_recoleccion,
            'condiciones_ambientales' => $request->condiciones_ambientales,
            'estado_muestra' => $request->estado_muestra,
        ]);

        return redirect()->route('muestras.index')
            ->with('success', 'Muestra actualizada exitosamente.');
    }

    /**
     * Eliminar muestra
     */
    public function destroy(MuestraEstudio $muestra)
    {
        // Verificar que la muestra pertenece a la empresa del usuario
        $this->authorize($muestra);

        $muestra->delete();

        return redirect()->route('muestras.index')
            ->with('success', 'Muestra eliminada exitosamente.');
    }

    /**
     * Helper para autorización
     */
    private function authorize(MuestraEstudio $muestra)
    {
        if ($muestra->estudio->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
