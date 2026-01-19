<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sensor;
use App\Models\TipoSensor;
use App\Models\UnidadMedida;
use App\Models\Empresa;
use App\Models\DispositivoMqtt;
use App\Models\Incubadora;
use App\Models\IncubadoraSensor;
use Illuminate\Support\Facades\DB;

class SensorPLCSeeder extends Seeder
{
    /**
     * Crea los 12 sensores exactos del PLC V4
     * 
     * Cada sensor puede enviar múltiples parámetros (tipos + unidades)
     * Los parámetros se almacenan en la tabla sensor_tipo_unidad
     * 
     * GRUPO 1 (Sensores 1-6):
     *   S1: pH + Temperatura
     *   S2: Conductividad Eléctrica
     *   S3: Oxígeno Disuelto
     *   S4: Nitrato + Temperatura
     *   S5: Amonio + Temperatura
     *   S6: Turbidez + Temperatura
     *
     * GRUPO 2 (Sensores 7-12): Idéntico a Grupo 1
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Limpiar tablas relacionadas
        DB::table('sensor_tipo_unidad')->truncate();
        DB::table('sensores')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Obtener datos base
        $empresa = Empresa::first();
        $dispositivo = DispositivoMqtt::first();
        $incubadoras = Incubadora::all();

        if (!$empresa || !$dispositivo || $incubadoras->isEmpty()) {
            throw new \Exception('Datos base no encontrados. Ejecuta: php artisan migrate:fresh --seed');
        }

        echo "\n╔════════════════════════════════════════════════════════════════╗\n";
        echo "║  🌡️  Creando 12 Sensores del PLC V4 (con múltiples unidades) ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        // Crear tipos de sensores y unidades
        $tipos = $this->crearTiposSensores();
        $unidades = $this->crearUnidadesMedida();

        echo "✅ Tipos de sensores creados\n";
        echo "✅ Unidades de medida creadas\n\n";

        // Definición de los 12 sensores exactos del PLC
        // Cada sensor puede tener múltiples tipos y unidades en sensor_tipo_unidad
        $sensoresDefinicion = [
            // GRUPO 1
            [
                'numero' => 1,
                'grupo' => 1,
                'nombre' => 'Sensor pH + Temperatura (G1-S1)',
                'codigo' => 'PH-TEMP-G1-S001',
                'tema_mqtt' => 'grupo1/sensor1',
                'descripcion' => 'Sensor combinado: pH y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'pH', 'unidad' => 'pH'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 2,
                'grupo' => 1,
                'nombre' => 'Sensor Conductividad Eléctrica (G1-S2)',
                'codigo' => 'CONDUCT-G1-S002',
                'tema_mqtt' => 'grupo1/sensor2',
                'descripcion' => 'Sensor de Conductividad Eléctrica',
                'tipos_unidades' => [
                    ['tipo' => 'Conductividad Eléctrica', 'unidad' => 'mS/cm'],
                ],
            ],
            [
                'numero' => 3,
                'grupo' => 1,
                'nombre' => 'Sensor Oxígeno Disuelto (G1-S3)',
                'codigo' => 'OXYGEN-G1-S003',
                'tema_mqtt' => 'grupo1/sensor3',
                'descripcion' => 'Sensor de Oxígeno Disuelto',
                'tipos_unidades' => [
                    ['tipo' => 'Oxígeno Disuelto', 'unidad' => 'mg/L'],
                ],
            ],
            [
                'numero' => 4,
                'grupo' => 1,
                'nombre' => 'Sensor Nitrato + Temperatura (G1-S4)',
                'codigo' => 'NITRATO-TEMP-G1-S004',
                'tema_mqtt' => 'grupo1/sensor4',
                'descripcion' => 'Sensor combinado: Nitrato y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Nitrato', 'unidad' => 'mg/L'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 5,
                'grupo' => 1,
                'nombre' => 'Sensor Amonio + Temperatura (G1-S5)',
                'codigo' => 'AMONIO-TEMP-G1-S005',
                'tema_mqtt' => 'grupo1/sensor5',
                'descripcion' => 'Sensor combinado: Amonio y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Amonio', 'unidad' => 'mg/L'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 6,
                'grupo' => 1,
                'nombre' => 'Sensor Turbidez + Temperatura (G1-S6)',
                'codigo' => 'TURBIDEZ-TEMP-G1-S006',
                'tema_mqtt' => 'grupo1/sensor6',
                'descripcion' => 'Sensor combinado: Turbidez y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Turbidez', 'unidad' => 'NTU'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],

            // GRUPO 2
            [
                'numero' => 7,
                'grupo' => 2,
                'nombre' => 'Sensor pH + Temperatura (G2-S7)',
                'codigo' => 'PH-TEMP-G2-S007',
                'tema_mqtt' => 'grupo2/sensor7',
                'descripcion' => 'Sensor combinado: pH y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'pH', 'unidad' => 'pH'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 8,
                'grupo' => 2,
                'nombre' => 'Sensor Conductividad Eléctrica (G2-S8)',
                'codigo' => 'CONDUCT-G2-S008',
                'tema_mqtt' => 'grupo2/sensor8',
                'descripcion' => 'Sensor de Conductividad Eléctrica',
                'tipos_unidades' => [
                    ['tipo' => 'Conductividad Eléctrica', 'unidad' => 'mS/cm'],
                ],
            ],
            [
                'numero' => 9,
                'grupo' => 2,
                'nombre' => 'Sensor Oxígeno Disuelto (G2-S9)',
                'codigo' => 'OXYGEN-G2-S009',
                'tema_mqtt' => 'grupo2/sensor9',
                'descripcion' => 'Sensor de Oxígeno Disuelto',
                'tipos_unidades' => [
                    ['tipo' => 'Oxígeno Disuelto', 'unidad' => 'mg/L'],
                ],
            ],
            [
                'numero' => 10,
                'grupo' => 2,
                'nombre' => 'Sensor Nitrato + Temperatura (G2-S10)',
                'codigo' => 'NITRATO-TEMP-G2-S010',
                'tema_mqtt' => 'grupo2/sensor10',
                'descripcion' => 'Sensor combinado: Nitrato y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Nitrato', 'unidad' => 'mg/L'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 11,
                'grupo' => 2,
                'nombre' => 'Sensor Amonio + Temperatura (G2-S11)',
                'codigo' => 'AMONIO-TEMP-G2-S011',
                'tema_mqtt' => 'grupo2/sensor11',
                'descripcion' => 'Sensor combinado: Amonio y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Amonio', 'unidad' => 'mg/L'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
            [
                'numero' => 12,
                'grupo' => 2,
                'nombre' => 'Sensor Turbidez + Temperatura (G2-S12)',
                'codigo' => 'TURBIDEZ-TEMP-G2-S012',
                'tema_mqtt' => 'grupo2/sensor12',
                'descripcion' => 'Sensor combinado: Turbidez y Temperatura',
                'tipos_unidades' => [
                    ['tipo' => 'Turbidez', 'unidad' => 'NTU'],
                    ['tipo' => 'Temperatura', 'unidad' => '°C'],
                ],
            ],
        ];

        echo "🌡️  Creando 12 sensores del PLC:\n\n";

        $sensoresCreados = 0;
        $incubadoraIndex = 0;

        foreach ($sensoresDefinicion as $def) {
            // Crear sensor
            $sensor = Sensor::updateOrCreate(
                ['codigo' => $def['codigo']],
                [
                    'nombre' => $def['nombre'],
                    'descripcion' => $def['descripcion'],
                    'id_dispositivo_mqtt' => $dispositivo->id,
                    'tema_mqtt' => $def['tema_mqtt'],
                    'id_empresa' => $empresa->id,
                    'estado' => 'activo',
                ]
            );

            // Vincular tipos y unidades (puede haber múltiples)
            foreach ($def['tipos_unidades'] as $tu) {
                $tipoSensor = $tipos[$tu['tipo']];
                $unidad = $unidades[$tu['unidad']];

                if (!DB::table('sensor_tipo_unidad')
                    ->where('sensor_id', $sensor->id)
                    ->where('tipo_sensor_id', $tipoSensor->id)
                    ->where('unidad_medida_id', $unidad->id)
                    ->exists()) {
                    
                    DB::table('sensor_tipo_unidad')->insert([
                        'sensor_id' => $sensor->id,
                        'tipo_sensor_id' => $tipoSensor->id,
                        'unidad_medida_id' => $unidad->id,
                        'decimales' => 2,
                        'factor_calibracion' => 1.0,
                        'activo' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            // Vincular a UNA SOLA incubadora por sensor
            if ($incubadoraIndex < $incubadoras->count()) {
                $incubadora = $incubadoras[$incubadoraIndex];
                
                IncubadoraSensor::updateOrCreate(
                    [
                        'id_incubadora' => $incubadora->id,
                        'id_sensor' => $sensor->id,
                    ],
                    [
                        'orden_posicion' => $def['numero'],
                        'activo' => true,
                    ]
                );

                // Mostrar progreso
                $sensoresCreados++;
                $parametros = implode(', ', array_map(fn($tu) => "{$tu['tipo']} ({$tu['unidad']})", $def['tipos_unidades']));
                echo "   ✅ S{$def['numero']}: {$def['nombre']}\n";
                echo "       • Código: {$def['codigo']}\n";
                echo "       • Parámetros: {$parametros}\n";
                echo "       • Incubadora: {$incubadora->nombre}\n";
                echo "       • Tema MQTT: {$def['tema_mqtt']}\n\n";

                $incubadoraIndex++;
            }
        }

        echo "╔════════════════════════════════════════════════════════════════╗\n";
        echo "║  ✅ {$sensoresCreados} sensores PLC creados exitosamente        ║\n";
        echo "╚════════════════════════════════════════════════════════════════╝\n\n";

        // Resumen
        echo "📊 RESUMEN:\n";
        echo "   • Incubadoras Grupo 1: 6 (con sensores S1-S6)\n";
        echo "   • Incubadoras Grupo 2: 6 (con sensores S7-S12)\n";
        echo "   • Total: 12 incubadoras con 12 sensores (1 sensor por incubadora)\n";
        echo "   • Sensores combinados: 6 (con múltiples parámetros)\n";
        echo "   • Sensores simples: 6 (un solo parámetro)\n";
        echo "   • Cada sensor puede enviar múltiples parámetros en un mensaje\n\n";
    }

    /**
     * Crear tipos de sensores individuales basados en los que envía el PLC
     */
    private function crearTiposSensores()
    {
        $tipos = [
            'pH' => [
                'nombre' => 'pH',
                'descripcion' => 'Sensor que mide el potencial de hidrógeno',
                'estado' => 'activo',
            ],
            'Temperatura' => [
                'nombre' => 'Temperatura',
                'descripcion' => 'Sensor que mide la temperatura del agua',
                'estado' => 'activo',
            ],
            'Conductividad Eléctrica' => [
                'nombre' => 'Conductividad Eléctrica',
                'descripcion' => 'Sensor que mide la conductividad eléctrica del agua',
                'estado' => 'activo',
            ],
            'Oxígeno Disuelto' => [
                'nombre' => 'Oxígeno Disuelto',
                'descripcion' => 'Sensor que mide el nivel de oxígeno disuelto en agua',
                'estado' => 'activo',
            ],
            'Nitrato' => [
                'nombre' => 'Nitrato',
                'descripcion' => 'Sensor que mide la concentración de nitrato',
                'estado' => 'activo',
            ],
            'Amonio' => [
                'nombre' => 'Amonio',
                'descripcion' => 'Sensor que mide la concentración de amonio',
                'estado' => 'activo',
            ],
            'Turbidez' => [
                'nombre' => 'Turbidez',
                'descripcion' => 'Sensor que mide la turbidez del agua',
                'estado' => 'activo',
            ],
        ];

        $resultado = [];

        foreach ($tipos as $clave => $datos) {
            $tipo = TipoSensor::updateOrCreate(
                ['nombre' => $datos['nombre']],
                ['descripcion' => $datos['descripcion'], 'activo' => true]
            );
            $resultado[$clave] = $tipo;
        }

        return $resultado;
    }

    /**
     * Crear unidades de medida individuales basadas en los que envía el PLC
     */
    private function crearUnidadesMedida()
    {
        $unidades = [
            'pH' => [
                'nombre' => 'pH',
                'descripcion' => 'Potencial de hidrógeno',
                'simbolo' => 'pH',
                'activo' => true,
            ],
            '°C' => [
                'nombre' => 'Grados Celsius',
                'descripcion' => 'Unidad de temperatura',
                'simbolo' => '°C',
                'activo' => true,
            ],
            'mS/cm' => [
                'nombre' => 'Milisemens por centímetro',
                'descripcion' => 'Unidad de conductividad eléctrica',
                'simbolo' => 'mS/cm',
                'activo' => true,
            ],
            'mg/L' => [
                'nombre' => 'Miligramos por litro',
                'descripcion' => 'Unidad de concentración',
                'simbolo' => 'mg/L',
                'activo' => true,
            ],
            'NTU' => [
                'nombre' => 'Unidades Nefelométricas de Turbidez',
                'descripcion' => 'Unidad de turbidez',
                'simbolo' => 'NTU',
                'activo' => true,
            ],
        ];

        $resultado = [];

        foreach ($unidades as $clave => $datos) {
            // Buscar por símbolo primero (más específico)
            $unidad = UnidadMedida::where('simbolo', $datos['simbolo'])->first();
            
            if (!$unidad) {
                $unidad = UnidadMedida::create([
                    'nombre' => $datos['nombre'],
                    'descripcion' => $datos['descripcion'],
                    'simbolo' => $datos['simbolo'],
                    'activo' => true,
                ]);
            }
            
            $resultado[$clave] = $unidad;
        }

        return $resultado;
    }
}
