<?php

namespace App\Imports;

use App\Models\Producto;
use App\Models\Categoria;
use Exception;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductosImport implements ToModel, WithHeadingRow
{
    public $categorias_creadas = 0;
    public $creados = 0;
    public $actualizados = 0;
    public $fila = 0;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->fila++;
        //dd($row);
        $categoria = Categoria::firstOrCreate(['nombre' => $row['categoria']]);
        if ($categoria->wasRecentlyCreated) {
            $this->categorias_creadas++;
        }
        $producto = Producto::updateOrCreate(
            ['sku' => $row['codigo']],
            [
                'nombre' => $row['nombre'] ?? $row['descripcion'],
                'descripcion' => $row['descripcion'] ?? null,
                'oem1' => $row['oem1'] ?? null,
                'oem2' => $row['oem2'] ?? null,
                'oem3' => $row['oem3'] ?? null,
                'oem4' => $row['oem4'] ?? null,
                'imagen' => $row['codigo'],
                'costo' => $row['costo'],
                'precio' => $row['precio'] ?? throw new Exception('Precio no definido'),
                'alto' => $row['alto'] ?? null,
                'ancho' => $row['ancho'] ?? null,
                'largo' => $row['largo'] ?? null,
                'peso' => $row['peso'] ?? null,
                'stock' => $row['stock'],
                'categoria_id' => $categoria->id,
            ]
        );
        if ($producto->wasRecentlyCreated) {
            $this->creados++;
        } else {
            $this->actualizados++;
        }
        return $producto;
    }
}
