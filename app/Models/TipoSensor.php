<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoSensor extends Model
{
    use HasFactory;

    protected $table = 'tipo_sensores';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación: Un tipo de sensor tiene muchos sensores
     */
    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'id_tipo_sensor');
    }

    /**
     * Relación Many-to-Many: Este tipo está asociado a muchos sensores
     * A través de la tabla intermedia sensor_tipo_unidad
     */
    public function sensoresToMany(): BelongsToMany
    {
        return $this->belongsToMany(
            Sensor::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'tipo_sensor_id',          // FK en tabla intermedia para este modelo
            'sensor_id',               // FK en tabla intermedia para Sensor
            'id',                      // PK de este modelo
            'id'                       // PK de Sensor
        )->withPivot(
            'unidad_medida_id',
            'minimo_optimo',
            'maximo_optimo',
            'minimo_critico',
            'maximo_critico',
            'decimales',
            'factor_calibracion',
            'activo'
        )->withTimestamps();
    }

    /**
     * Obtener todas las asignaciones de tipo-unidad para este tipo de sensor
     */
    public function sensorTipoUnidades()
    {
        return $this->hasMany(SensorTipoUnidad::class, 'tipo_sensor_id');
    }

    /**
     * Obtener todas las unidades de medida usadas con este tipo
     */
    public function unidadesMedida(): BelongsToMany
    {
        return $this->belongsToMany(
            UnidadMedida::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'tipo_sensor_id',          // FK en tabla intermedia para este modelo
            'unidad_medida_id',        // FK en tabla intermedia para UnidadMedida
            'id',                      // PK de este modelo
            'id'                       // PK de UnidadMedida
        )->distinct();
    }

    /**
     * Scope para obtener solo tipos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
