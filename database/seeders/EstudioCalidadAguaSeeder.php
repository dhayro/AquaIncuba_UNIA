<?php

namespace Database\Seeders;

use App\Models\EstudioCalidadAgua;
use App\Models\Incubadora;
use App\Models\Empresa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstudioCalidadAguaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener la primera empresa
        $empresa = Empresa::first();
        if (!$empresa) {
            $empresa = Empresa::create([
                'nombre' => 'Empresa Test',
                'razon_social' => 'Empresa Test S.A.',
            ]);
        }

        // Obtener todas las incubadoras de la empresa
        $allIncubadoras = Incubadora::where('id_empresa', $empresa->id)->pluck('id')->toArray();
        
        if (count($allIncubadoras) < 12) {
            echo "Se necesitan al menos 12 incubadoras para este seed (se encontraron " . count($allIncubadoras) . ")\n";
            return;
        }

        // Dividir en dos grupos de 6 (Grupo 1 y Grupo 2)
        $incubadoras1 = array_slice($allIncubadoras, 0, 6);   // INC-G1-001 a INC-G1-006
        $incubadoras2 = array_slice($allIncubadoras, 6, 6);   // INC-G2-007 a INC-G2-012

        $estudios = [
            [
                'nombre' => 'Estudio Grupo 1 - Incubadoras 1-6',
                'descripcion' => 'Estudio de calidad con incubadoras Grupo 1 (S1-S6)',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addDays(60),
                'estado' => 'planificado',
                'notas' => 'Estudio utilizando primer grupo de incubadoras con sensores del Grupo 1',
                'incubadoras' => $incubadoras1,
            ],
            [
                'nombre' => 'Estudio Grupo 2 - Incubadoras 7-12',
                'descripcion' => 'Estudio de calidad con incubadoras Grupo 2 (S7-S12)',
                'fecha_inicio' => now(),
                'fecha_fin' => now()->addDays(60),
                'estado' => 'planificado',
                'notas' => 'Estudio utilizando segundo grupo de incubadoras con sensores del Grupo 2',
                'incubadoras' => $incubadoras2,
            ],
        ];

        $year = date('Y');

        foreach ($estudios as $estudioData) {
            $incubadorasAsignadas = $estudioData['incubadoras'];
            unset($estudioData['incubadoras']);

            // Generar código automático
            $lastEstudio = EstudioCalidadAgua::where('id_empresa', $empresa->id)
                ->where('codigo_estudio', 'like', "EST-UNIA-{$year}-%")
                ->orderBy('codigo_estudio', 'desc')
                ->first();
            
            if ($lastEstudio) {
                preg_match('/EST-UNIA-\d{4}-(\d+)/', $lastEstudio->codigo_estudio, $matches);
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }
            
            $codigoEstudio = 'EST-UNIA-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $estudio = EstudioCalidadAgua::create([
                ...$estudioData,
                'codigo_estudio' => $codigoEstudio,
                'id_empresa' => $empresa->id,
                'id_creado_por' => 1,
            ]);

            // Asignar incubadoras
            foreach ($incubadorasAsignadas as $index => $incubadoraId) {
                $estudio->incubadoras()->attach($incubadoraId, [
                    'orden_posicion' => $index,
                ]);
            }

            echo "✓ Estudio '{$estudio->nombre}' creado con código {$codigoEstudio} y " . count($incubadorasAsignadas) . " incubadoras\n";
        }
    }
}
