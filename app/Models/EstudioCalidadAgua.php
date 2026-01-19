<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EstudioCalidadAgua extends Model
{
    use HasFactory;

    protected $table = 'estudios_calidad_agua';
    protected $guarded = [];
    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    /**
     * Relación M:M con Incubadoras
     * Un estudio puede tener múltiples incubadoras
     * Una incubadora puede pertenecer a múltiples estudios
     */
    public function incubadoras(): BelongsToMany
    {
        return $this->belongsToMany(
            Incubadora::class,
            'estudio_incubadora',
            'estudio_id',
            'incubadora_id'
        )
        ->withPivot('orden_posicion', 'notas')
        ->withTimestamps()
        ->orderBy('orden_posicion');
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

    /**
     * Verificar si el estudio está activo
     * Activo = estado = 'en_progreso' Y fecha_fin >= hoy
     */
    public function isActivo(): bool
    {
        return strtolower($this->estado) === 'en_progreso' && 
               (!$this->fecha_fin || $this->fecha_fin->format('Y-m-d') >= now()->format('Y-m-d'));
    }

    /**
     * Verificar si la fecha fin ha pasado
     */
    public function haTerminado(): bool
    {
        return $this->fecha_fin && $this->fecha_fin->format('Y-m-d') < now()->format('Y-m-d');
    }

    /**
     * Marcar estudio como finalizado
     */
    public function marcarFinalizado(): void
    {
        $this->update(['estado' => 'finalizado']);
    }
}
