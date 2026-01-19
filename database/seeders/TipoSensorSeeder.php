<?php

namespace Database\Seeders;

use App\Models\TipoSensor;
use Illuminate\Database\Seeder;

class TipoSensorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tiposSensores = [
            [
                'nombre' => 'Temperatura',
                'descripcion' => 'Sensor para medir temperatura del agua',
                'activo' => true,
            ],
            [
                'nombre' => 'pH',
                'descripcion' => 'Sensor para medir el pH del agua',
                'activo' => true,
            ],
            [
                'nombre' => 'Oxígeno Disuelto',
                'descripcion' => 'Sensor para medir oxígeno disuelto en el agua',
                'activo' => true,
            ],
            [
                'nombre' => 'Conductividad',
                'descripcion' => 'Sensor para medir conductividad eléctrica del agua',
                'activo' => true,
            ],
            [
                'nombre' => 'Turbidez',
                'descripcion' => 'Sensor para medir turbidez del agua',
                'activo' => true,
            ],
            [
                'nombre' => 'Salinidad',
                'descripcion' => 'Sensor para medir salinidad del agua',
                'activo' => true,
            ],
        ];

        foreach ($tiposSensores as $tipo) {
            TipoSensor::updateOrCreate(
                ['nombre' => $tipo['nombre']],
                $tipo
            );
        }
    }
}
