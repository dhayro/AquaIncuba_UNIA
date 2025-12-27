<?php

namespace App\Http\Controllers;

use App\Models\Incubadora;
use App\Models\Sensor;
use App\Models\LecturaSensor;
use App\Models\EstudioCalidadAgua;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard principal
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;

        // Estadísticas principales
        $totalIncubadoras = Incubadora::where('id_empresa', $empresaId)->count();
        $totalSensores = Sensor::where('id_empresa', $empresaId)->count();
        $totalEstudios = EstudioCalidadAgua::whereHas('incubadora', function ($query) use ($empresaId) {
            $query->where('id_empresa', $empresaId);
        })->count();
        $totalUsuarios = Usuario::where('id_empresa', $empresaId)->count();

        // Incubadoras activas
        $incubadorasActivas = Incubadora::where('id_empresa', $empresaId)
            ->with('sensores')
            ->limit(5)
            ->get();

        // Lecturas recientes
        $lecturasRecientes = LecturaSensor::whereHas('incubadora', function ($query) use ($empresaId) {
                $query->where('id_empresa', $empresaId);
            })
            ->with(['sensor', 'incubadora'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Estudios en progreso
        $estudiosActivos = EstudioCalidadAgua::whereHas('incubadora', function ($query) use ($empresaId) {
                $query->where('id_empresa', $empresaId);
            })
            ->where(function ($query) {
                $query->whereNull('fecha_fin')
                      ->orWhere('fecha_fin', '>', now());
            })
            ->with('incubadora')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'totalIncubadoras' => $totalIncubadoras,
            'totalSensores' => $totalSensores,
            'totalEstudios' => $totalEstudios,
            'totalUsuarios' => $totalUsuarios,
            'incubadorasActivas' => $incubadorasActivas,
            'lecturasRecientes' => $lecturasRecientes,
            'estudiosActivos' => $estudiosActivos,
            'title' => 'Dashboard',
            'catName' => 'dashboard',
            'scrollspy' => false,
        ]);
    }
}
