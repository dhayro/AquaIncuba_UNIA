<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\Empresa;
use App\Models\DispositivoMqtt;
use App\Models\Incubadora;
use App\Models\IncubadoraSensor;

class SensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Crea 18 sensores, cada uno vinculado a una incubadora
     * Todos los sensores se conectan al Dispositivo MQTT Principal
     */
    public function run(): void
    {
        $empresa = Empresa::first();
        
        if (!$empresa) {
            $this->command->error('No se encontró una empresa. Ejecuta DatabaseSeeder primero.');
            return;
        }

        // Obtener Dispositivo MQTT Principal
        $dispositivoMqtt = DispositivoMqtt::where('id_empresa', $empresa->id)
            ->where('nombre', 'like', '%Principal%')
            ->first();
        
        if (!$dispositivoMqtt) {
            $this->command->error('No se encontró Dispositivo MQTT Principal.');
            return;
        }

        // Obtener incubadoras (debe haber al menos 18)
        $incubadoras = Incubadora::where('id_empresa', $empresa->id)
            ->orderBy('id')
            ->take(18)
            ->get();

        if ($incubadoras->count() < 18) {
            $this->command->error('Se necesitan al menos 18 incubadoras. Se encontraron: ' . $incubadoras->count());
            return;
        }

        // Crear 18 sensores, cada uno para una incubadora
        $tiposSensores = ['Temperatura', 'pH', 'Oxígeno Disuelto', 'Conductividad', 'Turbidez', 'Multi-parámetro'];
        $sensoresCreados = [];

        foreach ($incubadoras as $index => $incubadora) {
            $numero = $index + 1;
            $tipoIndex = $index % count($tiposSensores);
            $tipo = $tiposSensores[$tipoIndex];

            $sensorData = [
                'codigo' => strtoupper(str_replace(' ', '-', $tipo)) . '-' . str_pad($numero, 3, '0', STR_PAD_LEFT),
                'nombre' => "Sensor $tipo $numero",
                'descripcion' => "Sensor de $tipo para Incubadora $numero",
                'id_dispositivo_mqtt' => $dispositivoMqtt->id,  // ✅ Vinculado al dispositivo principal
                'tema_mqtt' => "sensores/incubadora$numero/" . strtolower(str_replace(' ', '_', $tipo)),
                'id_empresa' => $empresa->id,
                'estado' => 'activo',
            ];

            $sensor = Sensor::updateOrCreate(
                ['codigo' => $sensorData['codigo']],
                $sensorData
            );

            // Vincular sensor a incubadora
            IncubadoraSensor::updateOrCreate(
                [
                    'id_incubadora' => $incubadora->id,
                    'id_sensor' => $sensor->id,
                ],
                [
                    'activo' => true,
                ]
            );

            $sensoresCreados[] = $sensor->nombre;
            $this->command->line("  ✓ {$sensor->nombre} → {$incubadora->nombre}");
        }

        $this->command->info("✅ 18 Sensores creados exitosamente y vinculados a incubadoras");
        $this->command->info("✅ Todos los sensores conectados al Dispositivo MQTT Principal");
    }
}
