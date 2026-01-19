<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstudioCalidadAgua;
use App\Models\MqttLectura;
use App\Models\SensorParametroMapping;
use Illuminate\Http\Request;

class EstudioDatosController extends Controller
{
    /**
     * Listar estudios por estado (en_progreso o finalizado)
     */
    public function index()
    {
        $empresaId = auth()->user()->id_empresa;

        $estudios = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->whereIn('estado', ['en_progreso', 'finalizado'])
            ->with('incubadoras')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.estudios-datos.index', [
            'estudios' => $estudios,
            'title' => 'Datos de Estudios',
            'catName' => 'estudios-datos',
        ]);
    }

    /**
     * Ver detalles de un estudio con datos de incubadoras
     */
    public function show(EstudioCalidadAgua $estudio)
    {
        $estudio->load('incubadoras.sensores');

        // Obtener lecturas por incubadora
        $incubadorasData = [];

        foreach ($estudio->incubadoras as $incubadora) {
            $sensoresData = [];

            foreach ($incubadora->sensores as $sensor) {
                $mappings = SensorParametroMapping::where('sensor_id', $sensor->id)
                    ->where('id_estudio', $estudio->id)
                    ->get();

                foreach ($mappings as $mapping) {
                    // Obtener todas las lecturas para este parámetro
                    $lecturas = MqttLectura::where('id_estudio', $estudio->id)
                        ->where('id_parametro', $mapping->id_parametro)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    if ($lecturas->count() > 0) {
                        $valores = $lecturas->pluck('valor')->filter(fn($v) => $v !== null);
                        $tiposensor = $mapping->parametro->tipoSensor->nombre ?? 'N/A';
                        $unidad = $mapping->parametro->unidadMedida->nombre ?? 'N/A';

                        $sensoresData[] = [
                            'nombre' => $sensor->nombre,
                            'parametro' => $tiposensor,
                            'unidad' => $unidad,
                            'total_lecturas' => $lecturas->count(),
                            'lecturas_validas' => $valores->count(),
                            'valor_minimo' => $valores->count() > 0 ? $valores->min() : null,
                            'valor_maximo' => $valores->count() > 0 ? $valores->max() : null,
                            'valor_promedio' => $valores->count() > 0 ? $valores->avg() : null,
                            'ultima_lectura' => $lecturas->last()->created_at ?? null,
                            'ultima_lectura_valor' => $lecturas->last()->valor ?? null,
                        ];
                    }
                }
            }

            if (count($sensoresData) > 0) {
                $incubadorasData[] = [
                    'incubadora' => $incubadora,
                    'sensores' => $sensoresData,
                ];
            }
        }

        return view('admin.estudios-datos.show', [
            'estudio' => $estudio,
            'incubadorasData' => $incubadorasData,
            'title' => 'Datos del Estudio: ' . $estudio->nombre,
            'catName' => 'estudios-datos',
        ]);
    }

    /**
     * Buscar estudio por código
     */
    public function buscar(Request $request)
    {
        $codigo = $request->input('codigo');
        $empresaId = auth()->user()->id_empresa;

        if (!$codigo) {
            return back()->with('error', 'Ingresa un código de estudio');
        }

        $estudio = EstudioCalidadAgua::where('id_empresa', $empresaId)
            ->where('codigo_estudio', 'like', "%{$codigo}%")
            ->first();

        if (!$estudio) {
            return back()->with('error', "No se encontró estudio con código: {$codigo}");
        }

        return redirect()->route('estudios-datos.show', $estudio->id);
    }
}
