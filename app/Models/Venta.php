<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Venta extends Model
{
    use HasFactory, LogsActivity;

    /**
     * Configuración del registro de actividades.
     *
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fecha_venta', 'expiracion_oferta', 'cliente_id', 'vendedor_id', 'estado'])
            ->logOnlyDirty()
            ->useLogName('venta');
    }

    protected $table = 'ventas';

    protected $fillable = [
        'fecha_venta',
        'expiracion_oferta',
        'cliente_id',
        'vendedor_id',
        'estado',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'producto_venta')
            ->withPivot('cantidad', 'precio_venta')
            ->withTimestamps();
    }
}
