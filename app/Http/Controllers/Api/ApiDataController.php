<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoSensor;
use App\Models\UnidadMedida;
use App\Models\DispositivoMqtt;

class ApiDataController extends Controller
{
    /**
     * Obtener todos los tipos de sensores
     */
    public function tiposSensores()
    {
        return response()->json(
            TipoSensor::where('activo', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre'])
        );
    }

    /**
     * Obtener todas las unidades de medida
     */
    public function unidadesMedida()
    {
        return response()->json(
            UnidadMedida::where('activo', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre', 'simbolo'])
        );
    }

    /**
     * Obtener todos los dispositivos MQTT
     */
    public function dispositivosMqtt()
    {
        return response()->json(
            DispositivoMqtt::where('esta_activo', true)
                ->orderBy('nombre')
                ->get(['id', 'nombre'])
        );
    }
}
