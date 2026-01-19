<?php

namespace Database\Seeders;

use App\Models\DispositivoMqtt;
use App\Models\TemaMqtt;
use App\Models\LogMqtt;
use Illuminate\Database\Seeder;

class LogMqttSeeder extends Seeder
{
    /**
     * Crear logs MQTT de prueba
     * 
     * Ejecutar con:
     * php artisan db:seed --class=LogMqttSeeder
     * 
     * NOTA: Esto es completamente opcional. En producción, 
     * los logs se generan automáticamente cuando el sistema
     * recibe mensajes MQTT del broker.
     */
    public function run(): void
    {
        // Obtener dispositivos MQTT
        $dispositivos = DispositivoMqtt::all();

        if ($dispositivos->isEmpty()) {
            $this->command->warn('⚠️ No hay dispositivos MQTT registrados.');
            return;
        }

        // Obtener temas MQTT (o crearlos si no existen)
        $temas = TemaMqtt::all();

        if ($temas->isEmpty()) {
            $this->command->warn('⚠️ No hay temas MQTT registrados.');
            return;
        }

        // Mensajes de ejemplo para simular MQTT
        $ejemplosMensajes = [
            [
                'tema' => 'sensores/incubadora1/temperatura',
                'contenido' => json_encode(['valor' => 25.5, 'timestamp' => now()]),
                'valor' => 25.5,
                'unidad' => '°C',
            ],
            [
                'tema' => 'sensores/incubadora1/ph',
                'contenido' => json_encode(['valor' => 7.2, 'timestamp' => now()]),
                'valor' => 7.2,
                'unidad' => 'pH',
            ],
            [
                'tema' => 'sensores/incubadora2/temperatura',
                'contenido' => json_encode(['valor' => 24.8, 'timestamp' => now()]),
                'valor' => 24.8,
                'unidad' => '°C',
            ],
            [
                'tema' => 'sensores/incubadora2/oxigeno_disuelto',
                'contenido' => json_encode(['valor' => 7.5, 'timestamp' => now()]),
                'valor' => 7.5,
                'unidad' => 'mg/L',
            ],
            [
                'tema' => 'estado/dispositivo-001',
                'contenido' => json_encode(['estado' => 'activo', 'timestamp' => now()]),
                'valor' => null,
                'unidad' => null,
            ],
        ];

        $totalLogs = 0;

        foreach ($dispositivos as $dispositivo) {
            // Crear algunos logs por dispositivo
            foreach ($ejemplosMensajes as $ejemplo) {
                // Obtener o crear tema
                $temaMqtt = $temas->first() ?? TemaMqtt::first();

                if (!$temaMqtt) {
                    continue;
                }

                LogMqtt::create([
                    'id_dispositivo_mqtt' => $dispositivo->id,
                    'id_tema_mqtt' => $temaMqtt->id,
                    'tema' => $ejemplo['tema'],
                    'contenido' => $ejemplo['contenido'],
                    'valor' => $ejemplo['valor'],
                    'unidad' => $ejemplo['unidad'],
                    'es_valido' => true,
                    'fecha_grabacion' => now()->subMinutes(rand(1, 60)),
                ]);

                $totalLogs++;
            }
        }

        $this->command->info("✅ {$totalLogs} logs MQTT creados para testing");
    }
}
