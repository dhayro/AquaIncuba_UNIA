<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorTipoUnidad extends Model
{
    use HasFactory;

    protected $table = 'sensor_tipo_unidad';
    protected $guarded = [];

    protected $casts = [
        'minimo_optimo' => 'decimal:4',
        'maximo_optimo' => 'decimal:4',
        'minimo_critico' => 'decimal:4',
        'maximo_critico' => 'decimal:4',
        'factor_calibracion' => 'decimal:6',
        'decimales' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * El sensor al que pertenece esta asignación
     */
    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    /**
     * El tipo de sensor para esta asignación
     */
    public function tipoSensor(): BelongsTo
    {
        return $this->belongsTo(TipoSensor::class, 'tipo_sensor_id');
    }

    /**
     * La unidad de medida para esta asignación
     */
    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    /**
     * Scope: Solo asignaciones activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope: Filtrar por sensor
     */
    public function scopePorSensor($query, $sensorId)
    {
        return $query->where('sensor_id', $sensorId);
    }

    /**
     * Scope: Filtrar por tipo de sensor
     */
    public function scopePorTipo($query, $tipoId)
    {
        return $query->where('tipo_sensor_id', $tipoId);
    }

    /**
     * Scope: Filtrar por unidad de medida
     */
    public function scopePorUnidad($query, $unidadId)
    {
        return $query->where('unidad_medida_id', $unidadId);
    }
}
