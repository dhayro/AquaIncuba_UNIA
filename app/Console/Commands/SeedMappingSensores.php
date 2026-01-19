<?php

namespace App\Console\Commands;

use App\Models\SensorParametroMapping;
use App\Models\EstudioCalidadAgua;
use App\Models\ParametroEstudio;
use Illuminate\Console\Command;

class SeedMappingSensores extends Command
{
    protected $signature = 'seed:mapping-sensores';
    protected $description = 'Crea mappings de sensores PLC a parámetros de estudio';

    public function handle()
    {
        $this->info("\n═══════════════════════════════════════════════════════════════");
        $this->info("📝 Creando mappings de sensores PLC (12 sensores)");
        $this->info("═══════════════════════════════════════════════════════════════\n");

        try {
            // Ejecutar el seeder mejorado con todos los 12 sensores
            $seeder = new \Database\Seeders\SeedMappingSensoresActualizado();
            $seeder->run();

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return 1;
        }
    }
}
