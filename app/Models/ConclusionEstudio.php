<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConclusionEstudio extends Model
{
    use HasFactory;

    protected $table = 'conclusiones_estudio';
    protected $guarded = [];

    public function estudioCalidad(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }

    public function usuarioConclusor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_concluido_por');
    }
}
