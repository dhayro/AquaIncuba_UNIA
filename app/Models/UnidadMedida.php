<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';

    protected $fillable = [
        'nombre',
        'simbolo',
        'descripcion',
        'tipo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Una unidad de medida tiene muchos sensores
     */
    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'id_unidad_medida');
    }

    /**
     * Relación Many-to-Many: Esta unidad está asociada a muchos sensores
     * A través de la tabla intermedia sensor_tipo_unidad
     */
    public function sensoresToMany(): BelongsToMany
    {
        return $this->belongsToMany(
            Sensor::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'unidad_medida_id',        // FK en tabla intermedia para este modelo
            'sensor_id',               // FK en tabla intermedia para Sensor
            'id',                      // PK de este modelo
            'id'                       // PK de Sensor
        )->withTimestamps();
    }

    /**
     * Relación Many-to-Many: Esta unidad está asociada a muchos tipos de sensor
     */
    public function tiposSensor(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoSensor::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'unidad_medida_id',        // FK en tabla intermedia para este modelo
            'tipo_sensor_id',          // FK en tabla intermedia para TipoSensor
            'id',                      // PK de este modelo
            'id'                       // PK de TipoSensor
        )->distinct();
    }

    /**
     * Obtener todas las asignaciones que usan esta unidad
     */
    public function sensorTipoUnidades()
    {
        return $this->hasMany(SensorTipoUnidad::class, 'unidad_medida_id');
    }

    /**
     * Scope para obtener solo unidades activas
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }
}
