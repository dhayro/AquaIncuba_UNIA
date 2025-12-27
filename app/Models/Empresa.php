<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $guarded = [];

    // Relaciones
    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_empresa');
    }

    public function incubadoras(): HasMany
    {
        return $this->hasMany(Incubadora::class, 'id_empresa');
    }

    public function sensores(): HasMany
    {
        return $this->hasMany(Sensor::class, 'id_empresa');
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_empresa');
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Rol::class, 'id_empresa');
    }

    public function configuracionMqtt(): HasOne
    {
        return $this->hasOne(ConfiguracionMqtt::class, 'id_empresa');
    }

    public function dispositivosMqtt(): HasMany
    {
        return $this->hasMany(DispositivoMqtt::class, 'id_empresa');
    }

    public function temasMqtt(): HasMany
    {
        return $this->hasMany(TemaMqtt::class, 'id_empresa');
    }

    public function parametrosEstudio(): HasMany
    {
        return $this->hasMany(ParametroEstudio::class, 'id_empresa');
    }

    public function estudiosCalidadAgua(): HasMany
    {
        return $this->hasMany(EstudioCalidadAgua::class, 'id_empresa');
    }

    public function alertasMqtt(): HasMany
    {
        return $this->hasMany(AlertaMqtt::class, 'id_empresa');
    }
}
