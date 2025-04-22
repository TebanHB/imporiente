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
        'sku',
        'nombre',
        'oem1',
        'oem2',
        'oem3',
        'oem4',
        'descripcion',
        'imagen',
        'costo_yen',
        'costo_usd',
        'costo_clp',
        'precio',
        'alto',
        'ancho',
        'largo',
        'peso',
        'stock',
        'categoria_id',
        'tipo_de_vehiculo',
        'origen',
        'ubicacion',
    ];

    protected $casts = [
        'costo_yen' => 'decimal:2',
        'costo_usd' => 'decimal:2',
        'costo_clp' => 'decimal:2',
        'precio'    => 'decimal:2',
        'alto'      => 'decimal:2',
        'ancho'     => 'decimal:2',
        'largo'     => 'decimal:2',
        'peso'      => 'decimal:2',
        'stock'     => 'integer',
    ];
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marcas()
    {
        return $this->belongsToMany(Marca::class);
    }

    public function modelos()
    {
        return $this->belongsToMany(Modelo::class);
    }

    /**
     * Configuración del registro de actividades.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName('producto');
    }
}
