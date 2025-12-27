<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstudioCalidadAgua extends Model
{
    use HasFactory;

    protected $table = 'estudios_calidad_agua';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function incubadora(): BelongsTo
    {
        return $this->belongsTo(Incubadora::class, 'id_incubadora');
    }

    public function usuarioCreador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_creado_por');
    }

    public function muestrasEstudio(): HasMany
    {
        return $this->hasMany(MuestraEstudio::class, 'id_estudio_calidad');
    }

    public function conclusionEstudio()
    {
        return $this->hasOne(ConclusionEstudio::class, 'id_estudio_calidad');
    }

    public function alertasMqtt(): HasMany
    {
        return $this->hasMany(AlertaMqtt::class, 'id_estudio_calidad');
    }
}
