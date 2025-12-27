<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_empresa',
        'nombre',
        'correo',
        'contraseña',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'contraseña',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'correo_verificado_en' => 'datetime',
            'contraseña' => 'hashed',
        ];
    }

    // Relaciones
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function rolesUsuarios(): HasMany
    {
        return $this->hasMany(RolUsuario::class, 'id_usuario');
    }

    public function roles()
    {
        return $this->hasManyThrough(
            Rol::class,
            RolUsuario::class,
            'id_usuario',
            'id',
            'id',
            'id_rol'
        );
    }

    public function estudiosCreados(): HasMany
    {
        return $this->hasMany(EstudioCalidadAgua::class, 'id_creado_por');
    }

    public function datosProcessadosRevisados(): HasMany
    {
        return $this->hasMany(DatoProcessadoEstudio::class, 'id_revisado_por');
    }

    public function conclusionesRealizadas(): HasMany
    {
        return $this->hasMany(ConclusionEstudio::class, 'id_concluido_por');
    }
}
