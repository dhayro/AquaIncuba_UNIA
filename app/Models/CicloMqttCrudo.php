<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicloMqttCrudo extends Model
{
    protected $table = 'ciclos_mqtt_crudos';
    protected $fillable = [
        'ciclo_numero',
        'payload_json',
        'estado',
        'error_mensaje'
    ];
    protected $casts = [
        'payload_json' => 'json',
    ];

    public function ciclosProcesados()
    {
        return $this->hasMany(CiclosProcessado::class, 'ciclos_mqtt_crudo_id');
    }
}
