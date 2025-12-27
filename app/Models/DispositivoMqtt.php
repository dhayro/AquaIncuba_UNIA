<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DispositivoMqtt extends Model
{
    use HasFactory;

    protected $table = 'dispositivos_mqtt';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function sensores(): HasMany
    {
        return $this->hasMany(Sensor::class, 'id_dispositivo_mqtt');
    }

    public function logsMqtt(): HasMany
    {
        return $this->hasMany(LogMqtt::class, 'id_dispositivo_mqtt');
    }
}
