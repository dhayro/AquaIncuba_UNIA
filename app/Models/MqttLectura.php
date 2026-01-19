<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MqttLectura extends Model
{
    use HasFactory;

    protected $table = 'mqtt_lecturas';
    protected $guarded = [];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio');
    }

    public function parametro(): BelongsTo
    {
        return $this->belongsTo(SensorParametroMapping::class, 'id_parametro');
    }

    public function ciclo(): BelongsTo
    {
        return $this->belongsTo(CicloMqttCrudo::class, 'ciclos_mqtt_crudo_id');
    }

    /**
     * Obtener nombre del tipo de sensor (parámetro)
     */
    public function getNombreParametroAttribute()
    {
        $mapping = SensorParametroMapping::find($this->id_parametro);
        if (!$mapping) return 'N/A';

        $stu = SensorTipoUnidad::find($mapping->id_parametro);
        if (!$stu) return 'N/A';

        $tipo = TipoSensor::find($stu->tipo_sensor_id);
        return $tipo->nombre ?? 'N/A';
    }

    /**
     * Obtener unidad de medida
     */
    public function getUnidadAttribute()
    {
        $mapping = SensorParametroMapping::find($this->id_parametro);
        if (!$mapping) return 'N/A';

        $stu = SensorTipoUnidad::find($mapping->id_parametro);
        if (!$stu) return 'N/A';

        $unidad = UnidadMedida::find($stu->unidad_medida_id);
        return $unidad->nombre ?? 'N/A';
    }

    /**
     * Obtener nombre del sensor
     */
    public function getNombreSensorAttribute()
    {
        $mapping = SensorParametroMapping::find($this->id_parametro);
        return $mapping ? $mapping->sensor_nombre : 'N/A';
    }
}
