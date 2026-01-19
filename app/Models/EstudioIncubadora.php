<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo para la tabla de asociación estudios_incubadoras
 * Representa la relación M:M entre Estudios e Incubadoras
 */
class EstudioIncubadora extends Model
{
    use HasFactory;

    protected $table = 'estudios_incubadoras';
    protected $guarded = [];
    protected $casts = [
        'fecha_agregada' => 'datetime',
    ];

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio_calidad');
    }

    public function incubadora(): BelongsTo
    {
        return $this->belongsTo(Incubadora::class, 'id_incubadora');
    }
}
