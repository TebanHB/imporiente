<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    
}
