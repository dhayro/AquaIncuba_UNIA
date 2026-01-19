<?php

namespace App\Console\Commands;

use App\Services\ProcesarCiclosService;
use Illuminate\Console\Command;

class ProcesarCiclosMqtt extends Command
{
    protected $signature = 'mqtt:procesar-ciclos';
    protected $description = 'Procesa ciclos MQTT pendientes y los inserta en LecturaSensor';

    public function handle()
    {
        $service = new ProcesarCiclosService();
        $cantidad = $service->procesarCiclosPendientes();
        
        $this->info("✅ Procesados {$cantidad} ciclos MQTT");
    }
}
