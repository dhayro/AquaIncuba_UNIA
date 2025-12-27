<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatoProcessadoEstudio;
use App\Models\EstudioCalidadAgua;
use Illuminate\Http\Request;

class DatoProcessadoEstudioController extends Controller
{
    /**
     * Mostrar listado de datos procesados de estudio
     */
    public function index()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener datos procesados de estudios de la empresa
        $datos = DatoProcessadoEstudio::whereHas('muestraEstudio.estudioCalidad', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })->with('muestraEstudio.estudioCalidad.incubadora', 'sensor', 'usuarioRevisor')->paginate(20);

        return view('admin.datos-estudio.procesados.index', compact('datos', 'catName'));
    }

    /**
     * Mostrar formulario para crear datos procesados
     */
    public function create()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener estudios completados
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->where('estado', 'completado')
            ->with('incubadora')
            ->get();

        if ($estudios->isEmpty()) {
            return redirect()->route('estudios.index')
                ->with('warning', 'No hay estudios completados para procesar datos.');
        }

        return view('admin.datos-estudio.procesados.create', compact('estudios', 'catName'));
    }

    /**
     * Guardar nuevos datos procesados
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_estudio_calidad_agua' => 'required|exists:estudios_calidad_agua,id',
            'parametro' => 'required|string|in:temperatura,ph,conductividad,oxigeno_disuelto,turbidez',
            'promedio' => 'required|numeric',
            'minimo' => 'required|numeric',
            'maximo' => 'required|numeric',
            'desviacion_estandar' => 'nullable|numeric|min:0',
            'notas_procesamiento' => 'nullable|string|max:1000',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Verificar que el estudio pertenece a la empresa del usuario
        $estudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->findOrFail($request->id_estudio_calidad_agua);

        DatoProcessadoEstudio::create([
            'id_estudio_calidad_agua' => $request->id_estudio_calidad_agua,
            'parametro' => $request->parametro,
            'promedio' => $request->promedio,
            'minimo' => $request->minimo,
            'maximo' => $request->maximo,
            'desviacion_estandar' => $request->desviacion_estandar,
            'notas_procesamiento' => $request->notas_procesamiento,
        ]);

        return redirect()->route('datos-procesados.index')
            ->with('success', 'Datos procesados guardados exitosamente.');
    }

    /**
     * Mostrar detalles de dato procesado
     */
    public function show(DatoProcessadoEstudio $datoProcessado)
    {
        $catName = 'estudios';
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoProcessado);

        return view('admin.datos-estudio.procesados.show', compact('datoProcessado', 'catName'));
    }

    /**
     * Mostrar formulario para editar dato procesado
     */
    public function edit(DatoProcessadoEstudio $datoProcessado)
    {
        $catName = 'estudios';
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoProcessado);

        $empresaId = auth()->user()->id_empresa;
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->with('incubadora')
            ->get();

        return view('admin.datos-estudio.procesados.edit', compact('datoProcessado', 'estudios', 'catName'));
    }

    /**
     * Actualizar dato procesado
     */
    public function update(Request $request, DatoProcessadoEstudio $datoProcessado)
    {
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoProcessado);

        $request->validate([
            'parametro' => 'required|string|in:temperatura,ph,conductividad,oxigeno_disuelto,turbidez',
            'promedio' => 'required|numeric',
            'minimo' => 'required|numeric',
            'maximo' => 'required|numeric',
            'desviacion_estandar' => 'nullable|numeric|min:0',
            'notas_procesamiento' => 'nullable|string|max:1000',
        ]);

        $datoProcessado->update([
            'parametro' => $request->parametro,
            'promedio' => $request->promedio,
            'minimo' => $request->minimo,
            'maximo' => $request->maximo,
            'desviacion_estandar' => $request->desviacion_estandar,
            'notas_procesamiento' => $request->notas_procesamiento,
        ]);

        return redirect()->route('datos-procesados.index')
            ->with('success', 'Datos procesados actualizados exitosamente.');
    }

    /**
     * Eliminar dato procesado
     */
    public function destroy(DatoProcessadoEstudio $datoProcessado)
    {
        // Verificar que el dato pertenece a la empresa del usuario
        $this->authorize($datoProcessado);

        $datoProcessado->delete();

        return redirect()->route('datos-procesados.index')
            ->with('success', 'Datos procesados eliminados exitosamente.');
    }

    /**
     * Helper para autorización
     */
    private function authorize(DatoProcessadoEstudio $dato)
    {
        if ($dato->muestraEstudio->estudioCalidad->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
