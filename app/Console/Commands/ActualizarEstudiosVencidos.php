<?php

namespace App\Console\Commands;

use App\Models\EstudioCalidadAgua;
use Illuminate\Console\Command;

class ActualizarEstudiosVencidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estudios:actualizar-vencidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza automáticamente el estado de estudios cuya fecha fin ha pasado a "finalizado"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $hoy = now()->format('Y-m-d');
            
            // Buscar estudios en progreso con fecha_fin vencida
            $estudiosVencidos = EstudioCalidadAgua::where('estado', 'en_progreso')
                ->whereNotNull('fecha_fin')
                ->whereDate('fecha_fin', '<', $hoy)
                ->get();

            if ($estudiosVencidos->isEmpty()) {
                $this->info('✅ No hay estudios vencidos para actualizar');
                return Command::SUCCESS;
            }

            $cantidad = $estudiosVencidos->count();

            foreach ($estudiosVencidos as $estudio) {
                $estudio->marcarFinalizado();
                $this->info("✅ Estudio '{$estudio->nombre}' marcado como Finalizado (fecha fin: {$estudio->fecha_fin->format('d/m/Y')})");
            }

            $this->info("\n✅ Total de estudios actualizados: {$cantidad}");
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
