<?php

namespace Database\Seeders;

use App\Models\UnidadMedida;
use Illuminate\Database\Seeder;

class UnidadMedidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [
            // Temperatura
            [
                'nombre' => 'Grados Celsius',
                'simbolo' => '°C',
                'tipo' => 'temperatura',
                'descripcion' => 'Temperatura en grados Celsius',
                'activo' => true,
            ],
            [
                'nombre' => 'Grados Fahrenheit',
                'simbolo' => '°F',
                'tipo' => 'temperatura',
                'descripcion' => 'Temperatura en grados Fahrenheit',
                'activo' => true,
            ],
            [
                'nombre' => 'Kelvin',
                'simbolo' => 'K',
                'tipo' => 'temperatura',
                'descripcion' => 'Temperatura en Kelvin',
                'activo' => true,
            ],
            
            // pH
            [
                'nombre' => 'pH',
                'simbolo' => 'pH',
                'tipo' => 'ph',
                'descripcion' => 'Escala de pH (0-14)',
                'activo' => true,
            ],
            
            // Concentración
            [
                'nombre' => 'Miligramos por Litro',
                'simbolo' => 'mg/L',
                'tipo' => 'concentracion',
                'descripcion' => 'Concentración en miligramos por litro',
                'activo' => true,
            ],
            [
                'nombre' => 'Partes por Millón',
                'simbolo' => 'ppm',
                'tipo' => 'concentracion',
                'descripcion' => 'Concentración en partes por millón',
                'activo' => true,
            ],
            [
                'nombre' => 'Gramos por Litro',
                'simbolo' => 'g/L',
                'tipo' => 'concentracion',
                'descripcion' => 'Concentración en gramos por litro',
                'activo' => true,
            ],
            
            // Conductividad
            [
                'nombre' => 'Milisiemens por Centímetro',
                'simbolo' => 'mS/cm',
                'tipo' => 'conductividad',
                'descripcion' => 'Conductividad eléctrica en milisiemens por centímetro',
                'activo' => true,
            ],
            [
                'nombre' => 'Microsiemens por Centímetro',
                'simbolo' => 'µS/cm',
                'tipo' => 'conductividad',
                'descripcion' => 'Conductividad eléctrica en microsiemens por centímetro',
                'activo' => true,
            ],
            
            // Turbidez
            [
                'nombre' => 'Unidades de Turbidez Nefelométrica',
                'simbolo' => 'NTU',
                'tipo' => 'turbidez',
                'descripcion' => 'Unidades de turbidez nefelométrica',
                'activo' => true,
            ],
            [
                'nombre' => 'Unidades de Turbidez Formazina',
                'simbolo' => 'FTU',
                'tipo' => 'turbidez',
                'descripcion' => 'Unidades de turbidez formazina',
                'activo' => true,
            ],
            
            // Salinidad
            [
                'nombre' => 'Partes por Mil',
                'simbolo' => 'ppt',
                'tipo' => 'salinidad',
                'descripcion' => 'Salinidad en partes por mil',
                'activo' => true,
            ],
            [
                'nombre' => 'PSU',
                'simbolo' => 'PSU',
                'tipo' => 'salinidad',
                'descripcion' => 'Practical Salinity Unit',
                'activo' => true,
            ],
            
            // Presión
            [
                'nombre' => 'Bares',
                'simbolo' => 'bar',
                'tipo' => 'presion',
                'descripcion' => 'Presión en bares',
                'activo' => true,
            ],
            [
                'nombre' => 'Atmósferas',
                'simbolo' => 'atm',
                'tipo' => 'presion',
                'descripcion' => 'Presión en atmósferas',
                'activo' => true,
            ],
            [
                'nombre' => 'Pascales',
                'simbolo' => 'Pa',
                'tipo' => 'presion',
                'descripcion' => 'Presión en Pascales',
                'activo' => true,
            ],
            
            // Volumen
            [
                'nombre' => 'Litros',
                'simbolo' => 'L',
                'tipo' => 'volumen',
                'descripcion' => 'Volumen en litros',
                'activo' => true,
            ],
            [
                'nombre' => 'Mililitros',
                'simbolo' => 'mL',
                'tipo' => 'volumen',
                'descripcion' => 'Volumen en mililitros',
                'activo' => true,
            ],
            [
                'nombre' => 'Metros Cúbicos',
                'simbolo' => 'm³',
                'tipo' => 'volumen',
                'descripcion' => 'Volumen en metros cúbicos',
                'activo' => true,
            ],
        ];

        foreach ($unidades as $unidad) {
            UnidadMedida::updateOrCreate(
                ['nombre' => $unidad['nombre']],
                $unidad
            );
        }
    }
}
