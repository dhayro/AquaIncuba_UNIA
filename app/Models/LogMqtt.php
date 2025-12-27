<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogMqtt extends Model
{
    use HasFactory;

    protected $table = 'logs_mqtt';
    protected $guarded = [];

    public function dispositivoMqtt(): BelongsTo
    {
        return $this->belongsTo(DispositivoMqtt::class, 'id_dispositivo_mqtt');
    }

    public function temaMqtt(): BelongsTo
    {
        return $this->belongsTo(TemaMqtt::class, 'id_tema_mqtt');
    }

    // Alias para temaMqtt
    public function tema(): BelongsTo
    {
        return $this->belongsTo(TemaMqtt::class, 'id_tema_mqtt');
    }

    public function lecturasSensores()
    {
        return $this->hasMany(LecturaSensor::class, 'id_log_mqtt');
    }
}
