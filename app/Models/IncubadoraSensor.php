<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncubadoraSensor extends Model
{
    use HasFactory;

    protected $table = 'incubadoras_sensores';
    protected $guarded = [];

    public function incubadora(): BelongsTo
    {
        return $this->belongsTo(Incubadora::class, 'id_incubadora');
    }

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'id_sensor');
    }
}
