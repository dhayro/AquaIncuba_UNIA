<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sensor extends Model
{
    use HasFactory;

    protected $table = 'sensores';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function dispositivoMqtt(): BelongsTo
    {
        return $this->belongsTo(DispositivoMqtt::class, 'id_dispositivo_mqtt');
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
}
