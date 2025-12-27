<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConclusionEstudio;
use App\Models\EstudioCalidadAgua;
use Illuminate\Http\Request;

class ConclusionEstudioController extends Controller
{
    /**
     * Mostrar listado de conclusiones de estudio
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener conclusiones de estudios de la empresa
        $conclusiones = ConclusionEstudio::whereHas('estudio', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })->with('estudio.incubadora')->paginate(15);

        return view('admin.conclusiones-estudio.index', compact('conclusiones'), ['catName' => 'estudios']);
    }

    /**
     * Mostrar formulario para crear conclusión
     */
    public function create()
    {
        $catName = 'estudios';
        $empresaId = auth()->user()->id_empresa;
        
        // Obtener estudios completados o con datos procesados
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->whereIn('estado', ['completado', 'análisis'])
            ->with('incubadora')
            ->get();

        if ($estudios->isEmpty()) {
            return redirect()->route('estudios.index')
                ->with('warning', 'No hay estudios con datos procesados.');
        }

        return view('admin.conclusiones-estudio.create', compact('estudios', 'catName'));
    }

    /**
     * Guardar nueva conclusión
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_estudio_calidad_agua' => 'required|exists:estudios_calidad_agua,id',
            'titulo' => 'required|string|min:5|max:200',
            'contenido' => 'required|string|min:20|max:5000',
            'recomendaciones' => 'nullable|string|min:10|max:2000',
            'calidad_agua' => 'required|in:excelente,buena,aceptable,deficiente,muy_deficiente',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Verificar que el estudio pertenece a la empresa del usuario
        $estudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->findOrFail($request->id_estudio_calidad_agua);

        ConclusionEstudio::create([
            'id_estudio_calidad_agua' => $request->id_estudio_calidad_agua,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'recomendaciones' => $request->recomendaciones,
            'calidad_agua' => $request->calidad_agua,
        ]);

        return redirect()->route('conclusiones.index')
            ->with('success', 'Conclusión creada exitosamente.');
    }

    /**
     * Mostrar detalles de conclusión
     */
    public function show(ConclusionEstudio $conclusion)
    {
        $catName = 'estudios';
        // Verificar que la conclusión pertenece a la empresa del usuario
        $this->authorize($conclusion);

        return view('admin.conclusiones-estudio.show', compact('conclusion', 'catName'));
    }

    /**
     * Mostrar formulario para editar conclusión
     */
    public function edit(ConclusionEstudio $conclusion)
    {
        $catName = 'estudios';
        // Verificar que la conclusión pertenece a la empresa del usuario
        $this->authorize($conclusion);

        $empresaId = auth()->user()->id_empresa;
        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->with('incubadora')
            ->get();

        return view('admin.conclusiones-estudio.edit', compact('conclusion', 'estudios', 'catName'));
    }

    /**
     * Actualizar conclusión
     */
    public function update(Request $request, ConclusionEstudio $conclusion)
    {
        // Verificar que la conclusión pertenece a la empresa del usuario
        $this->authorize($conclusion);

        $request->validate([
            'titulo' => 'required|string|min:5|max:200',
            'contenido' => 'required|string|min:20|max:5000',
            'recomendaciones' => 'nullable|string|min:10|max:2000',
            'calidad_agua' => 'required|in:excelente,buena,aceptable,deficiente,muy_deficiente',
        ]);

        $conclusion->update([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'recomendaciones' => $request->recomendaciones,
            'calidad_agua' => $request->calidad_agua,
        ]);

        return redirect()->route('conclusiones.index')
            ->with('success', 'Conclusión actualizada exitosamente.');
    }

    /**
     * Eliminar conclusión
     */
    public function destroy(ConclusionEstudio $conclusion)
    {
        // Verificar que la conclusión pertenece a la empresa del usuario
        $this->authorize($conclusion);

        $conclusion->delete();

        return redirect()->route('conclusiones.index')
            ->with('success', 'Conclusión eliminada exitosamente.');
    }

    /**
     * Exportar conclusión a PDF
     */
    public function exportPdf(ConclusionEstudio $conclusion)
    {
        // Verificar que la conclusión pertenece a la empresa del usuario
        $this->authorize($conclusion);

        // Nota: Esta es una estructura preparada para integración con dompdf
        // Implementar: composer require barryvdh/laravel-dompdf
        
        return view('admin.conclusiones-estudio.pdf', compact('conclusion'));
    }

    /**
     * Helper para autorización
     */
    private function authorize(ConclusionEstudio $conclusion)
    {
        if ($conclusion->estudioCalidad->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
