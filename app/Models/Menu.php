<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $guarded = [];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'id_empresa');
    }

    public function padre(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_padre');
    }

    public function submenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'id_padre');
    }

    public function permisosRoles(): HasMany
    {
        return $this->hasMany(PermisoMenuRol::class, 'id_menu');
    }
}
