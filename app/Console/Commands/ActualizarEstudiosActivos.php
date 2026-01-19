<?php

namespace App\Console\Commands;

use App\Models\EstudioCalidadAgua;
use Illuminate\Console\Command;

class ActualizarEstudiosActivos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estudios:actualizar-activos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente estudios "Planificado" a "En Progreso" cuando llega la fecha inicio';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $hoy = now()->format('Y-m-d');
            
            // Buscar estudios planificados que han llegado a su fecha_inicio
            $estudiosParaActivar = EstudioCalidadAgua::where('estado', 'planificado')
                ->whereNotNull('fecha_inicio')
                ->whereDate('fecha_inicio', '<=', $hoy)
                ->get();

            if ($estudiosParaActivar->isEmpty()) {
                $this->info('✅ No hay estudios planificados que activar');
                return Command::SUCCESS;
            }

            $cantidad = $estudiosParaActivar->count();

            foreach ($estudiosParaActivar as $estudio) {
                $estudio->update(['estado' => 'en_progreso']);
                $this->info("✅ Estudio '{$estudio->nombre}' activado (fecha inicio: {$estudio->fecha_inicio->format('d/m/Y')})");
            }

            $this->info("\n✅ Total de estudios activados: {$cantidad}");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
