<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoVenta extends Model
{
    use HasFactory;

    protected $table = 'producto_venta';

    protected $fillable = [
        'producto_id',
        'venta_id',
        'cantidad',
        'precio_venta',
    ];
    
}
