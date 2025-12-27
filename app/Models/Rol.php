<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function rolesUsuarios(): HasMany
    {
        return $this->hasMany(RolUsuario::class, 'id_rol');
    }

    public function permisosMenus(): HasMany
    {
        return $this->hasMany(PermisoMenuRol::class, 'id_rol');
    }
}
