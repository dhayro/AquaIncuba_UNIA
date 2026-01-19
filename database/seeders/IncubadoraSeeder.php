<?php

namespace Database\Seeders;

use App\Models\Incubadora;
use Illuminate\Database\Seeder;

class IncubadoraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la empresa (la primera o crearla si no existe)
        $empresa = \App\Models\Empresa::first();
        if (!$empresa) {
            $this->command->error('No hay empresas en la base de datos. Ejecuta DatabaseSeeder primero.');
            return;
        }

        // Crear 12 incubadoras (6 Grupo 1 + 6 Grupo 2)
        $incubadorasData = [
            // GRUPO 1
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 1 (G1-S1)',
                'codigo' => 'INC-G1-001',
                'descripcion' => 'Tanque Grupo 1 - Sensor pH + Temperatura',
                'capacidad_tanque' => 500,
                'ubicacion' => 'Laboratorio A - Estante 1',
                'especificaciones' => 'Temperatura controlada: 25-28°C, Aireación continua',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 2 (G1-S2)',
                'codigo' => 'INC-G1-002',
                'descripcion' => 'Tanque Grupo 1 - Sensor Conductividad',
                'capacidad_tanque' => 400,
                'ubicacion' => 'Laboratorio A - Estante 2',
                'especificaciones' => 'Sistema de filtración avanzado',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 3 (G1-S3)',
                'codigo' => 'INC-G1-003',
                'descripcion' => 'Tanque Grupo 1 - Sensor Oxígeno Disuelto',
                'capacidad_tanque' => 300,
                'ubicacion' => 'Laboratorio A - Estante 3',
                'especificaciones' => 'Vidrio transparente, iluminación LED',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 4 (G1-S4)',
                'codigo' => 'INC-G1-004',
                'descripcion' => 'Tanque Grupo 1 - Sensor Nitrato + Temperatura',
                'capacidad_tanque' => 600,
                'ubicacion' => 'Laboratorio B - Estante 1',
                'especificaciones' => 'Sistema de reciclaje de agua',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 5 (G1-S5)',
                'codigo' => 'INC-G1-005',
                'descripcion' => 'Tanque Grupo 1 - Sensor Amonio + Temperatura',
                'capacidad_tanque' => 250,
                'ubicacion' => 'Laboratorio B - Estante 2',
                'especificaciones' => 'Temperatura constante 28°C',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 6 (G1-S6)',
                'codigo' => 'INC-G1-006',
                'descripcion' => 'Tanque Grupo 1 - Sensor Turbidez + Temperatura',
                'capacidad_tanque' => 200,
                'ubicacion' => 'Laboratorio B - Estante 3',
                'especificaciones' => 'Sistema de desinfección UV',
                'activo' => true,
            ],

            // GRUPO 2
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 7 (G2-S7)',
                'codigo' => 'INC-G2-007',
                'descripcion' => 'Tanque Grupo 2 - Sensor pH + Temperatura',
                'capacidad_tanque' => 350,
                'ubicacion' => 'Laboratorio C - Estante 1',
                'especificaciones' => 'Alimentación automática programable',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 8 (G2-S8)',
                'codigo' => 'INC-G2-008',
                'descripcion' => 'Tanque Grupo 2 - Sensor Conductividad',
                'capacidad_tanque' => 400,
                'ubicacion' => 'Laboratorio C - Estante 2',
                'especificaciones' => 'Rango: 20-35°C variable',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 9 (G2-S9)',
                'codigo' => 'INC-G2-009',
                'descripcion' => 'Tanque Grupo 2 - Sensor Oxígeno Disuelto',
                'capacidad_tanque' => 450,
                'ubicacion' => 'Laboratorio C - Estante 3',
                'especificaciones' => 'Ciclo luz-oscuridad programable',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 10 (G2-S10)',
                'codigo' => 'INC-G2-010',
                'descripcion' => 'Tanque Grupo 2 - Sensor Nitrato + Temperatura',
                'capacidad_tanque' => 800,
                'ubicacion' => 'Laboratorio D - Estante 1',
                'especificaciones' => 'Capacidad aumentada, sistema robusto',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 11 (G2-S11)',
                'codigo' => 'INC-G2-011',
                'descripcion' => 'Tanque Grupo 2 - Sensor Amonio + Temperatura',
                'capacidad_tanque' => 500,
                'ubicacion' => 'Laboratorio D - Estante 2',
                'especificaciones' => 'Sistema de respaldo automático',
                'activo' => true,
            ],
            [
                'id_empresa' => $empresa->id,
                'nombre' => 'Incubadora Experimental 12 (G2-S12)',
                'codigo' => 'INC-G2-012',
                'descripcion' => 'Tanque Grupo 2 - Sensor Turbidez + Temperatura',
                'capacidad_tanque' => 550,
                'ubicacion' => 'Laboratorio D - Estante 3',
                'especificaciones' => 'Sensores múltiples integrados',
                'activo' => true,
            ],
        ];

        // Crear las incubadoras
        foreach ($incubadorasData as $data) {
            $incubadora = Incubadora::create($data);
            $this->command->info("Incubadora '{$incubadora->nombre}' creada");
        }

        $this->command->info('12 incubadoras creadas exitosamente. (Los sensores se asignarán en SensorSeeder)');
    }
}

