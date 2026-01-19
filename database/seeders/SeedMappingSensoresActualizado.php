<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SensorParametroMapping;
use App\Models\ParametroEstudio;
use App\Models\Sensor;
use Illuminate\Support\Facades\DB;

class SeedMappingSensoresActualizado extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Los sensores que envía el PLC V4:
     * S1:  pH + Temperatura
     * S2:  Conductividad Eléctrica
     * S3:  Oxígeno Disuelto
     * S4:  Nitrato + Temperatura
     * S5:  Amonio + Temperatura
     * S6:  Turbidez + Temperatura
     * S7:  pH + Temperatura (Grupo 2)
     * S8:  Conductividad Eléctrica (Grupo 2)
     * S9:  Oxígeno Disuelto (Grupo 2)
     * S10: Nitrato + Temperatura (Grupo 2)
     * S11: Amonio + Temperatura (Grupo 2)
     * S12: Turbidez + Temperatura (Grupo 2)
     */
    public function run(): void
    {
        echo "\n";
        echo "╔═══════════════════════════════════════════════════════════════╗\n";
        echo "║  🌱 Creando Mappings de Sensores del PLC V4                 ║\n";
        echo "╚═══════════════════════════════════════════════════════════════╝\n";
        echo "\n";

        // Obtener el estudio activo
        $estudio = DB::table('estudios_calidad_agua')
                    ->where('estado', 'ACTIVO')
                    ->orWhere('estado', 'EN_PROGRESO')
                    ->first();

        if (!$estudio) {
            echo "❌ ERROR: No hay estudios activos en la BD\n";
            return;
        }

        $idEstudio = $estudio->id;
        echo "✅ Usando estudio: {$estudio->nombre} (ID: {$idEstudio})\n\n";

        // Limpiar mappings anteriores (opcional)
        DB::table('sensor_parametro_mapping')
            ->where('id_estudio', $idEstudio)
            ->delete();

        echo "🗑️  Mappings anteriores eliminados\n\n";

        // Array de mappings basado en sensores reales del PLC
        $mappings = [
            // GRUPO 1
            [
                'sensor_id' => 1,
                'sensor_nombre' => 'Sensor pH + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 1,  // pH del agua
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'pH',
                'activo' => 1,
                'notas' => 'Extrae pH del objeto combinado (pH+Temp)',
            ],
            [
                'sensor_id' => 1,
                'sensor_nombre' => 'Sensor pH + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (pH+Temp)',
            ],
            [
                'sensor_id' => 2,
                'sensor_nombre' => 'Sensor Conductividad Eléctrica (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 3,  // Conductividad eléctrica
                'tipo_extraccion' => 'directo',
                'clave_json' => null,
                'activo' => 1,
                'notas' => 'Valor directo en mS/cm',
            ],
            [
                'sensor_id' => 3,
                'sensor_nombre' => 'Sensor Oxígeno Disuelto (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 4,  // Oxígeno disuelto
                'tipo_extraccion' => 'directo',
                'clave_json' => null,
                'activo' => 1,
                'notas' => 'Valor directo en mg/L',
            ],
            [
                'sensor_id' => 4,
                'sensor_nombre' => 'Sensor Nitrato + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 5,  // Nitrato
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'nitrato',
                'activo' => 1,
                'notas' => 'Extrae nitrato del objeto combinado (Nitrato+Temp)',
            ],
            [
                'sensor_id' => 4,
                'sensor_nombre' => 'Sensor Nitrato + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Nitrato+Temp)',
            ],
            [
                'sensor_id' => 5,
                'sensor_nombre' => 'Sensor Amonio + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 6,  // Amonio
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'amonio',
                'activo' => 1,
                'notas' => 'Extrae amonio del objeto combinado (Amonio+Temp)',
            ],
            [
                'sensor_id' => 5,
                'sensor_nombre' => 'Sensor Amonio + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Amonio+Temp)',
            ],
            [
                'sensor_id' => 6,
                'sensor_nombre' => 'Sensor Turbidez + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 7,  // Turbidez
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'turbidez',
                'activo' => 1,
                'notas' => 'Extrae turbidez del objeto combinado (Turbidez+Temp)',
            ],
            [
                'sensor_id' => 6,
                'sensor_nombre' => 'Sensor Turbidez + Temperatura (Grupo 1)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Turbidez+Temp)',
            ],

            // GRUPO 2
            [
                'sensor_id' => 7,
                'sensor_nombre' => 'Sensor pH + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 1,  // pH del agua
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'pH',
                'activo' => 1,
                'notas' => 'Extrae pH del objeto combinado (pH+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 7,
                'sensor_nombre' => 'Sensor pH + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (pH+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 8,
                'sensor_nombre' => 'Sensor Conductividad Eléctrica (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 3,  // Conductividad eléctrica
                'tipo_extraccion' => 'directo',
                'clave_json' => null,
                'activo' => 1,
                'notas' => 'Valor directo en mS/cm - Grupo 2',
            ],
            [
                'sensor_id' => 9,
                'sensor_nombre' => 'Sensor Oxígeno Disuelto (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 4,  // Oxígeno disuelto
                'tipo_extraccion' => 'directo',
                'clave_json' => null,
                'activo' => 1,
                'notas' => 'Valor directo en mg/L - Grupo 2',
            ],
            [
                'sensor_id' => 10,
                'sensor_nombre' => 'Sensor Nitrato + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 5,  // Nitrato
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'nitrato',
                'activo' => 1,
                'notas' => 'Extrae nitrato del objeto combinado (Nitrato+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 10,
                'sensor_nombre' => 'Sensor Nitrato + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Nitrato+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 11,
                'sensor_nombre' => 'Sensor Amonio + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 6,  // Amonio
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'amonio',
                'activo' => 1,
                'notas' => 'Extrae amonio del objeto combinado (Amonio+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 11,
                'sensor_nombre' => 'Sensor Amonio + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Amonio+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 12,
                'sensor_nombre' => 'Sensor Turbidez + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 7,  // Turbidez
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'turbidez',
                'activo' => 1,
                'notas' => 'Extrae turbidez del objeto combinado (Turbidez+Temp) - Grupo 2',
            ],
            [
                'sensor_id' => 12,
                'sensor_nombre' => 'Sensor Turbidez + Temperatura (Grupo 2)',
                'id_estudio' => $idEstudio,
                'id_parametro' => 2,  // Temperatura
                'tipo_extraccion' => 'objeto',
                'clave_json' => 'temperatura',
                'activo' => 1,
                'notas' => 'Extrae temperatura del objeto combinado (Turbidez+Temp) - Grupo 2',
            ],
        ];

        // Insertar mappings
        foreach ($mappings as $mapping) {
            SensorParametroMapping::create($mapping);
            echo "✅ Creado: S{$mapping['sensor_id']} → P{$mapping['id_parametro']}\n";
        }

        echo "\n";
        echo "╔═══════════════════════════════════════════════════════════════╗\n";
        echo "║  ✅ " . count($mappings) . " mappings creados exitosamente                           ║\n";
        echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

        // Mostrar resumen
        echo "📊 RESUMEN DE MAPPINGS CREADOS:\n";
        echo "   • Total mappings: " . count($mappings) . "\n";
        echo "   • Sensores mapeados: 12 (6 de cada grupo)\n";
        echo "   • Parámetros utilizados: 7\n";
        echo "     1. pH del agua\n";
        echo "     2. Temperatura\n";
        echo "     3. Conductividad eléctrica\n";
        echo "     4. Oxígeno disuelto\n";
        echo "     5. Nitrato\n";
        echo "     6. Amonio\n";
        echo "     7. Turbidez\n";
        echo "\n";
        echo "✨ Los sensores del PLC están listos para procesar\n";
    }
}
