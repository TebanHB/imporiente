<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    
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
