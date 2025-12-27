<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstudioCalidadAgua;
use App\Models\Incubadora;
use App\Models\MuestraEstudio;
use Illuminate\Http\Request;

class EstudioCalidadAguaController extends Controller
{
    /**
     * Listar estudios
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;
        $estudios = EstudioCalidadAgua::whereHas('incubadora', function ($query) use ($empresaId) {
                $query->where('id_empresa', $empresaId);
            })
            ->with(['incubadora'])
            ->paginate(15);

        return view('admin.estudios.index', [
            'estudios' => $estudios,
            'title' => 'Gestión de Estudios de Calidad de Agua',
            'catName' => 'estudios',
        ]);
    }

    /**
     * Crear estudio
     */
    public function create()
    {
        $empresaId = auth()->user()->id_empresa;
        $incubadoras = Incubadora::where('id_empresa', $empresaId)
            ->with('sensores')
            ->get();

        return view('admin.estudios.create', [
            'incubadoras' => $incubadoras,
            'title' => 'Crear Estudio',
            'catName' => 'estudios',
        ]);
    }

    /**
     * Guardar nuevo estudio
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_incubadora' => 'required|exists:incubadoras,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'numero_muestras' => 'required|integer|min:1',
        ]);

        $estudio = EstudioCalidadAgua::create($validated);

        // Crear muestras vacías
        for ($i = 1; $i <= $validated['numero_muestras']; $i++) {
            MuestraEstudio::create([
                'id_estudio' => $estudio->id,
                'numero_muestra' => $i,
            ]);
        }

        return redirect()->route('estudios.show', $estudio->id)->with('success', 'Estudio creado exitosamente');
    }

    /**
     * Ver detalles del estudio
     */
    public function show(EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);
        
        $muestras = $estudio->muestras()->with(['datosCrudos', 'datosProcesados'])->get();

        return view('admin.estudios.show', [
            'estudio' => $estudio,
            'muestras' => $muestras,
            'title' => $estudio->nombre,
            'catName' => 'estudios',
        ]);
    }

    /**
     * Editar estudio
     */
    public function edit(EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);
        
        $empresaId = auth()->user()->id_empresa;
        $incubadoras = Incubadora::where('id_empresa', $empresaId)->get();

        return view('admin.estudios.edit', [
            'estudio' => $estudio,
            'incubadoras' => $incubadoras,
            'title' => 'Editar Estudio',
            'catName' => 'estudios',
        ]);
    }

    /**
     * Actualizar estudio
     */
    public function update(Request $request, EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
        ]);

        $estudio->update($validated);

        return redirect()->route('estudios.show', $estudio->id)->with('success', 'Estudio actualizado exitosamente');
    }

    /**
     * Eliminar estudio
     */
    public function destroy(EstudioCalidadAgua $estudio)
    {
        $this->checkAccess($estudio);

        $estudio->muestras()->each(function ($muestra) {
            $muestra->datosCrudos()->delete();
            $muestra->datosProcesados()->delete();
            $muestra->delete();
        });

        $estudio->delete();

        return redirect()->route('estudios.index')->with('success', 'Estudio eliminado exitosamente');
    }

    /**
     * Verificar acceso al estudio
     */
    private function checkAccess(EstudioCalidadAgua $estudio)
    {
        if ($estudio->incubadora->id_empresa !== auth()->user()->id_empresa) {
            abort(403);
        }
    }
}
