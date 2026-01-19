<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ConfigureMqttInterval extends Command
{
    protected $signature = 'mqtt:configure-interval {interval? : Intervalo en segundos (30, 60, 300, 900, etc.)}';
    protected $description = 'Configura el intervalo de procesamiento de ciclos MQTT';

    public function handle()
    {
        $interval = $this->argument('interval');

        if (!$interval) {
            // Mostrar opciones
            $this->info('╔══════════════════════════════════════════════════════╗');
            $this->info('║     Configuración de Intervalo MQTT                  ║');
            $this->info('╚══════════════════════════════════════════════════════╝');
            $this->newLine();

            $options = [
                '30' => 'Cada 30 segundos (RÁPIDO)',
                '60' => 'Cada minuto (RECOMENDADO - ACTUAL)',
                '300' => 'Cada 5 minutos',
                '900' => 'Cada 15 minutos',
                'custom' => 'Valor personalizado',
            ];

            $this->line('Opciones disponibles:');
            foreach ($options as $value => $label) {
                $this->line("  <fg=cyan>$value</> - $label");
            }

            $this->newLine();
            $choice = $this->choice(
                'Selecciona una opción',
                array_values($options),
                1 // Índice del valor por defecto (60 segundos)
            );

            // Obtener el valor seleccionado
            $interval = array_search($choice, $options);
            if ($interval === 'custom') {
                $interval = $this->ask('¿Cuántos segundos?');
            }
        }

        // Validar que sea un número
        if (!is_numeric($interval) || (int)$interval < 1) {
            $this->error('❌ El intervalo debe ser un número positivo');
            return 1;
        }

        $interval = (int)$interval;

        // Actualizar el .env
        $envFile = base_path('.env');
        $content = File::get($envFile);

        if (strpos($content, 'MQTT_PROCESSING_INTERVAL=') !== false) {
            $content = preg_replace(
                '/MQTT_PROCESSING_INTERVAL=\d+/',
                'MQTT_PROCESSING_INTERVAL=' . $interval,
                $content
            );
        } else {
            // Si no existe, agregarlo
            $content .= "\nMQTT_PROCESSING_INTERVAL=" . $interval;
        }

        File::put($envFile, $content);

        // Calcular formato legible
        $readable = $this->formatInterval($interval);

        $this->info("\n╔══════════════════════════════════════════════════════╗");
        $this->info("║         ✅ Intervalo Configurado                      ║");
        $this->info("╚══════════════════════════════════════════════════════╝\n");
        $this->line("  📌 Intervalo: <fg=green>$interval segundos</> ($readable)");
        $this->line("  📁 Archivo: <fg=cyan>.env</>");
        $this->newLine();

        $this->info("💡 Para aplicar los cambios, reinicia el scheduler:");
        $this->line("   <fg=yellow>php artisan schedule:work</>");
        $this->newLine();

        return 0;
    }

    /**
     * Convierte segundos a formato legible
     */
    private function formatInterval($seconds)
    {
        if ($seconds < 60) {
            return "$seconds segundos";
        } elseif ($seconds < 3600) {
            $minutes = $seconds / 60;
            return $minutes . ' minuto' . ($minutes > 1 ? 's' : '');
        } else {
            $hours = $seconds / 3600;
            return $hours . ' hora' . ($hours > 1 ? 's' : '');
        }
    }
}
