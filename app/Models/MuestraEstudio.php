<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MuestraEstudio extends Model
{
    use HasFactory;

    protected $table = 'muestras_estudio';
    protected $guarded = [];

    public function estudioCalidad(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }

    public function datosCrudos(): HasMany
    {
        return $this->hasMany(DatoCrudoEstudio::class, 'id_muestra_estudio');
    }

    public function datosProcessados(): HasMany
    {
        return $this->hasMany(DatoProcessadoEstudio::class, 'id_muestra_estudio');
    }
}
