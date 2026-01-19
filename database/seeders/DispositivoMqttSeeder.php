<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DispositivoMqtt;
use App\Models\Empresa;

class DispositivoMqttSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = Empresa::first();
        
        if (!$empresa) {
            $this->command->error('No se encontró una empresa. Ejecuta DatabaseSeeder primero.');
            return;
        }

        $dispositivos = [
            [
                'nombre' => 'Dispositivo MQTT Principal',
                'id_dispositivo' => 'mqtt-device-001',
                'tipo_dispositivo' => 'Puerta de enlace MQTT',
                'tema_mqtt' => 'sensores/dispositivo-001',
                'ubicacion' => 'Sala de control',
                'esta_activo' => true,
                'version_firmware' => '1.0.0',
            ],
            [
                'nombre' => 'Dispositivo MQTT Secundario',
                'id_dispositivo' => 'mqtt-device-002',
                'tipo_dispositivo' => 'Concentrador de datos',
                'tema_mqtt' => 'sensores/dispositivo-002',
                'ubicacion' => 'Laboratorio',
                'esta_activo' => true,
                'version_firmware' => '1.0.0',
            ],
        ];

        foreach ($dispositivos as $dispositivoData) {
            DispositivoMqtt::updateOrCreate(
                ['id_dispositivo' => $dispositivoData['id_dispositivo']],
                array_merge($dispositivoData, [
                    'id_empresa' => $empresa->id,
                ])
            );
        }

        $this->command->info('✅ Dispositivos MQTT creados exitosamente');
    }
}
