<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemaMqtt extends Model
{
    use HasFactory;

    protected $table = 'temas_mqtt';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function logsMqtt(): HasMany
    {
        return $this->hasMany(LogMqtt::class, 'id_tema_mqtt');
    }
}
