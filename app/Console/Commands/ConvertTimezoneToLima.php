<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConvertTimezoneToLima extends Command
{
    protected $signature = 'app:convert-timezone-to-lima';
    protected $description = 'Convierte todos los timestamps de UTC a América/Lima (UTC-5)';

    public function handle()
    {
        $this->info('Iniciando conversión de zona horaria a América/Lima...');

        // Actualizar mqtt_lecturas
        $this->updateTable('mqtt_lecturas', ['created_at', 'updated_at', 'fechaRegistro']);
        
        // Actualizar ciclos_mqtt_crudos
        $this->updateTable('ciclos_mqtt_crudos', ['created_at', 'updated_at', 'fecha_recibido', 'fecha_procesado']);
        
        // Actualizar estudios_calidad_agua
        $this->updateTable('estudios_calidad_agua', ['created_at', 'updated_at']);
        
        $this->info('✅ Conversión completada');
    }

    private function updateTable($table, $dateColumns)
    {
        $this->info("Procesando tabla: {$table}");
        
        $records = DB::table($table)->get();
        $convertidos = 0;

        foreach ($records as $record) {
            $updates = [];
            
            foreach ($dateColumns as $column) {
                if (!isset($record->$column) || is_null($record->$column)) {
                    continue;
                }

                try {
                    // Parsear la fecha como UTC
                    $date = Carbon::createFromFormat('Y-m-d H:i:s', $record->$column, 'UTC');
                    // Convertir a Lima (UTC-5)
                    $date->setTimezone('America/Lima');
                    $updates[$column] = $date->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    // Ignorar errores de formato
                    continue;
                }
            }

            if (!empty($updates)) {
                DB::table($table)->where('id', $record->id)->update($updates);
                $convertidos++;
            }
        }

        $this->info("  ✓ {$convertidos} registros convertidos");
    }
}
