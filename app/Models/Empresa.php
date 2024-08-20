<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Empresa extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'empresa'; // Especifica el nombre de la tabla si no sigue la convención de nombres

    protected $fillable = [
        'nombre',
        'pais',
        'numero',
        'ciudad',
        'estado',
        'calle',
        'impuestos',
        'ruat',
    ];

    /**
     * Configuración del registro de actividades.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nombre', 'ruat', 'pais', 'numero'])
            ->logOnlyDirty()
            ->useLogName('empresa');
    }
}