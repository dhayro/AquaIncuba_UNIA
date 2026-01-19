<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensores';
    protected $guarded = [];
    protected $casts = [
        'estado' => 'string',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function dispositivoMqtt(): BelongsTo
    {
        return $this->belongsTo(DispositivoMqtt::class, 'id_dispositivo_mqtt');
    }

    public function tipoSensor(): BelongsTo
    {
        return $this->belongsTo(TipoSensor::class, 'id_tipo_sensor');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'id_unidad_medida');
    }

    public function incubadorasSensores(): HasMany
    {
        return $this->hasMany(IncubadoraSensor::class, 'id_sensor');
    }

    public function incubadoras()
    {
        return $this->hasManyThrough(
            Incubadora::class,
            IncubadoraSensor::class,
            'id_sensor',
            'id',
            'id',
            'id_incubadora'
        );
    }

    public function lecturasSensores(): HasMany
    {
        return $this->hasMany(LecturaSensor::class, 'id_sensor');
    }

    public function datosCrudosEstudio(): HasMany
    {
        return $this->hasMany(DatoCrudoEstudio::class, 'id_sensor');
    }

    public function datosProcessadosEstudio(): HasMany
    {
        return $this->hasMany(DatoProcessadoEstudio::class, 'id_sensor');
    }

    public function alertasMqtt(): HasMany
    {
        return $this->hasMany(AlertaMqtt::class, 'id_sensor');
    }

    // ============================================
    // RELACIONES MANY-TO-MANY CON JUNCTION TABLE
    // ============================================

    /**
     * Obtener todas las asignaciones de tipo y unidad para este sensor
     * Relación: Un sensor tiene muchas asignaciones de tipo-unidad
     */
    public function sensorTipoUnidades()
    {
        return $this->hasMany(SensorTipoUnidad::class, 'sensor_id');
    }

    /**
     * Obtener todos los tipos de sensores que mide este sensor
     * A través de la tabla intermedia sensor_tipo_unidad
     */
    public function tiposSensor(): BelongsToMany
    {
        return $this->belongsToMany(
            TipoSensor::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'sensor_id',               // FK en tabla intermedia para este modelo
            'tipo_sensor_id',          // FK en tabla intermedia para TipoSensor
            'id',                      // PK de este modelo
            'id'                       // PK de TipoSensor
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
     * Obtener todas las unidades de medida usadas por este sensor
     * A través de la tabla intermedia sensor_tipo_unidad
     */
    public function unidadesMedida(): BelongsToMany
    {
        return $this->belongsToMany(
            UnidadMedida::class,
            'sensor_tipo_unidad',      // tabla intermedia
            'sensor_id',               // FK en tabla intermedia para este modelo
            'unidad_medida_id',        // FK en tabla intermedia para UnidadMedida
            'id',                      // PK de este modelo
            'id'                       // PK de UnidadMedida
        )->withTimestamps();
    }

    /**
     * Scope: Solo sensores con tipos y unidades activas
     */
    public function scopeConTiposActivos($query)
    {
        return $query->whereHas('sensorTipoUnidades', function ($q) {
            $q->where('activo', true);
        });
    }
}
