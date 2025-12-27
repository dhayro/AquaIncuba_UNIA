<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturaSensor extends Model
{
    use HasFactory;

    protected $table = 'lecturas_sensores';
    protected $guarded = [];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'id_sensor');
    }

    public function incubadora(): BelongsTo
    {
        return $this->belongsTo(Incubadora::class, 'id_incubadora');
    }

    public function logMqtt(): BelongsTo
    {
        return $this->belongsTo(LogMqtt::class, 'id_log_mqtt');
    }

    public function datosCrudosEstudio()
    {
        return $this->hasMany(DatoCrudoEstudio::class, 'id_lectura_sensor');
    }
}
