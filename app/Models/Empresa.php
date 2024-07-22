<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa'; // Especifica el nombre de la tabla si no sigue la convención de nombres

    protected $fillable = [
        'nombre','pais', 'numero', 'ciudad', 'estado', 'calle', 'impuestos',
    ];
    use HasFactory;
}
