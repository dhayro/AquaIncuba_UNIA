<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Incubadora extends Model
{
    use HasFactory;

    protected $table = 'incubadoras';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    /**
     * Relación 1:M con sensores
     * Una incubadora puede tener múltiples sensores
     */
    public function incubadoraSensores(): HasMany
    {
        return $this->hasMany(IncubadoraSensor::class, 'id_incubadora');
    }

    /**
     * Obtener sensores a través de la tabla junction
     */
    public function sensores()
    {
        return $this->hasManyThrough(
            Sensor::class,
            IncubadoraSensor::class,
            'id_incubadora',
            'id',
            'id',
            'id_sensor'
        );
    }

    /**
     * Relación M:M con Estudios
     * Una incubadora puede pertenecer a múltiples estudios
     * Un estudio puede tener múltiples incubadoras
     */
    public function estudios(): BelongsToMany
    {
        return $this->belongsToMany(
            EstudioCalidadAgua::class,
            'estudio_incubadora',
            'incubadora_id',
            'estudio_id'
        )
        ->withPivot('orden_posicion', 'notas')
        ->withTimestamps()
        ->orderBy('orden_posicion');
    }

    public function lecturasSensores(): HasMany
    {
        return $this->hasMany(LecturaSensor::class, 'id_incubadora');
    }

    public function alertasMqtt(): HasMany
    {
        return $this->hasMany(AlertaMqtt::class, 'id_incubadora');
    }
}
