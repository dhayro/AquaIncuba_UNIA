<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incubadora extends Model
{
    use HasFactory;

    protected $table = 'incubadoras';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function incubadoraSensores(): HasMany
    {
        return $this->hasMany(IncubadoraSensor::class, 'id_incubadora');
    }

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

    public function lecturasSensores(): HasMany
    {
        return $this->hasMany(LecturaSensor::class, 'id_incubadora');
    }

    public function estudios(): HasMany
    {
        return $this->hasMany(EstudioCalidadAgua::class, 'id_incubadora');
    }

    public function alertasMqtt(): HasMany
    {
        return $this->hasMany(AlertaMqtt::class, 'id_incubadora');
    }
}
