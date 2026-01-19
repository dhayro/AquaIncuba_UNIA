<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Intervalo de procesamiento de ciclos MQTT en segundos
     * Configurable: cambia este valor según necesites
     * 
     * Ejemplos:
     * - 30 = cada 30 segundos
     * - 60 = cada minuto (ACTUAL)
     * - 300 = cada 5 minutos
     * - 900 = cada 15 minutos
     * 
     * También puedes cambiar el valor en .env: MQTT_PROCESSING_INTERVAL
     */
    const MQTT_PROCESSING_INTERVAL = 60;

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Procesar ciclos MQTT cada minuto
        $schedule->command('mqtt:procesar-ciclos')
                 ->everyMinute()
                 ->withoutOverlapping(timeout: 30);

        // ✅ Activar estudios planificados CADA MINUTO (para detectar inmediatamente)
        $schedule->command('estudios:actualizar-activos')
                 ->everyMinute()
                 ->withoutOverlapping(timeout: 30);

        // Actualizar estudios vencidos diariamente a las 00:01
        $schedule->command('estudios:actualizar-vencidos')
                 ->dailyAt('00:01')
                 ->withoutOverlapping(timeout: 30);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
