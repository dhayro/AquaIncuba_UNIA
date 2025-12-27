<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogMqtt;
use App\Models\DispositivoMqtt;
use App\Models\TemaMqtt;
use Illuminate\Http\Request;

class LogMqttController extends Controller
{
    /**
     * Mostrar listado de logs MQTT (solo lectura)
     */
    public function index(Request $request)
    {
        $catName = 'monitoreo';
        $empresaId = auth()->user()->id_empresa;
        
        // Query base con filtros de empresa
        $query = LogMqtt::whereHas('dispositivoMqtt', function ($q) use ($empresaId) {
            $q->where('id_empresa', $empresaId);
        })->with('dispositivoMqtt', 'temaMqtt');

        // Filtro por dispositivo
        if ($request->filled('dispositivo_id')) {
            $query->where('id_dispositivo_mqtt', $request->dispositivo_id);
        }

        // Filtro por tema
        if ($request->filled('tema_id')) {
            $query->where('id_tema_mqtt', $request->tema_id);
        }

        // Filtro por rango de fechas
        if ($request->filled('desde')) {
            $query->whereDate('fecha_grabacion', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha_grabacion', '<=', $request->hasta);
        }

        // Ordenar por fecha descendente (más recientes primero)
        $logs = $query->orderBy('fecha_grabacion', 'desc')->paginate(20);

        // Obtener dispositivos para el filtro
        $dispositivos = DispositivoMqtt::where('id_empresa', $empresaId)->get();

        // Obtener temas para el filtro
        $temas = TemaMqtt::where('id_empresa', $empresaId)->get();

        return view('admin.logs-mqtt.index', compact('logs', 'dispositivos', 'temas', 'catName'));
    }

    /**
     * Mostrar detalles del log MQTT
     */
    public function show(LogMqtt $logMqtt)
    {
        $catName = 'monitoreo';
        // Verificar que el log pertenece a la empresa del usuario
        $this->authorize($logMqtt);

        return view('admin.logs-mqtt.show', compact('logMqtt', 'catName'));
    }

    /**
     * Exportar logs MQTT a CSV
     */
    public function export(Request $request)
    {
        $empresaId = auth()->user()->id_empresa;
        
        // Query base
        $query = LogMqtt::whereHas('dispositivoMqtt', function ($q) use ($empresaId) {
            $q->where('id_empresa', $empresaId);
        })->with('dispositivoMqtt', 'temaMqtt');

        // Aplicar mismos filtros que en index
        if ($request->filled('dispositivo_id')) {
            $query->where('id_dispositivo_mqtt', $request->dispositivo_id);
        }

        if ($request->filled('tema_id')) {
            $query->where('id_tema_mqtt', $request->tema_id);
        }

        if ($request->filled('desde')) {
            $query->whereDate('fecha_grabacion', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('fecha_grabacion', '<=', $request->hasta);
        }

        $logs = $query->orderBy('fecha_grabacion', 'desc')->get();

        // Generar CSV
        $filename = 'logs_mqtt_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Encabezados
            fputcsv($file, ['Dispositivo', 'Tema', 'Valor', 'Unidad', 'Fecha/Hora']);
            
            // Datos
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->dispositivoMqtt->nombre,
                    $log->temaMqtt->tema,
                    $log->valor,
                    $log->unidad,
                    $log->fecha_grabacion,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Limpiar logs antiguos
     */
    public function clean(Request $request)
    {
        $request->validate([
            'dias' => 'required|integer|min:1|max:365',
        ]);

        $empresaId = auth()->user()->id_empresa;
        
        // Eliminar logs más antiguos que X días
        $fecha_limite = now()->subDays($request->dias);
        
        $deleted = LogMqtt::whereHas('dispositivoMqtt', function ($q) use ($empresaId) {
            $q->where('id_empresa', $empresaId);
        })->where('fecha_grabacion', '<', $fecha_limite)->delete();

        return redirect()->route('logs-mqtt.index')
            ->with('success', "Se eliminaron $deleted registros de logs.");
    }

    /**
     * Helper para autorización
     */
    private function authorize(LogMqtt $log)
    {
        $empresaId = auth()->user()->id_empresa;
        
        if ($log->dispositivoMqtt->id_empresa !== $empresaId) {
            abort(403);
        }
    }
}
