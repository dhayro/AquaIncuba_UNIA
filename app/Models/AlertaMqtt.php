<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaMqtt extends Model
{
    use HasFactory;

    protected $table = 'alertas_mqtt';
    protected $guarded = [];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'id_sensor');
    }

    public function incubadora(): BelongsTo
    {
        return $this->belongsTo(Incubadora::class, 'id_incubadora');
    }

    public function estudioCalidad(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }
}
