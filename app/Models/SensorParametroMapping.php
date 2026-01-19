<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorParametroMapping extends Model
{
    protected $table = 'sensor_parametro_mapping';
    protected $fillable = [
        'sensor_id',
        'sensor_nombre',
        'id_estudio',
        'id_parametro',
        'tipo_extraccion',
        'clave_json',
        'activo',
        'notas'
    ];

    public function sensor(): BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    public function estudio(): BelongsTo
    {
        return $this->belongsTo(EstudioCalidadAgua::class, 'id_estudio');
    }

    public function parametro(): BelongsTo
    {
        return $this->belongsTo(SensorTipoUnidad::class, 'id_parametro');
    }

    public static function getMappingsForStudy($id_estudio)
    {
        return self::where('id_estudio', $id_estudio)
                   ->where('activo', true)
                   ->get();
    }
}
