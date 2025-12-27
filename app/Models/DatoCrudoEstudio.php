<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatoCrudoEstudio extends Model
{
    use HasFactory;

    protected $table = 'datos_crudos_estudio';
    protected $guarded = [];

    public function muestraEstudio(): BelongsTo
    {
        return $this->belongsTo(MuestraEstudio::class, 'id_muestra_estudio');
    }

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'id_sensor');
    }

    public function lecturaSensor(): BelongsTo
    {
        return $this->belongsTo(LecturaSensor::class, 'id_lectura_sensor');
    }

    // Crear un accessor para acceder a estudio a través de muestraEstudio
    public function getEstudioAttribute()
    {
        return $this->muestraEstudio?->estudioCalidad;
    }

    // Alias para muestra
    public function getMuestraAttribute()
    {
        return $this->muestraEstudio;
    }
}
