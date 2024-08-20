<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'oem1',
        'oem2',
        'oem3',
        'oem4',
        'descripcion',
        'imagen',
        'costo',
        'precio',
        'alto',
        'ancho',
        'largo',
        'peso',
        'stock',
        'categoria_id',
        'sku',
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marcas()
    {
        return $this->belongsToMany(Marca::class);
    }
    
     /**
     * Configuración del registro de actividades.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nombre',
                'oem1',
                'oem2',
                'oem3',
                'oem4',
                'descripcion',
                'imagen',
                'costo',
                'precio',
                'alto',
                'ancho',
                'largo',
                'peso',
                'stock',
                'categoria_id',
                'sku',
            ])
            ->logOnlyDirty()
            ->useLogName('producto');
    }
    
}
