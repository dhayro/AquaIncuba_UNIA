<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\CicloMqttCrudo;
use App\Http\Controllers\Api\EstudioDatosApiController;

// Endpoint para guardar ciclos crudos del PLC
// NOTA: Se permite guardar ciclos duplicados (mismo ciclo_numero)
// El PLC real puede enviar el mismo ciclo varias veces
Route::post('/ciclos-mqtt/guardar', function (Request $request) {
    try {
        // SIEMPRE crear un nuevo registro, incluso si ciclo_numero es duplicado
        // Esto permite registrar múltiples lecturas del mismo ciclo si el PLC lo reenvía
        $ciclo = CicloMqttCrudo::create([
            'ciclo_numero' => $request->ciclo_numero,
            'payload_json' => $request->payload_json,
            'estado' => 'PENDIENTE'
        ]);
        
        return response()->json([
            'ok' => true,
            'id' => $ciclo->id,
            'message' => 'Ciclo guardado correctamente',
            'ciclo_numero' => $ciclo->ciclo_numero
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'ok' => false,
            'error' => $e->getMessage()
        ], 400);
    }
});

// Rutas de datos históricos de estudios
Route::get('/estudios/{estudio}/sensor-datos/{incubadora}/{sensorNombre}/{parametro?}', 
    [EstudioDatosApiController::class, 'obtenerDatosSensor']);
