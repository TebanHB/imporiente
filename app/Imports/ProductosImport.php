<?php

namespace App\Imports;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\ShouldQueue;

class ProductosImport implements ToModel, WithHeadingRow
{
    public $categorias_creadas = 0;
    public $marcas_creadas     = 0;
    public $modelos_creados    = 0;
    public $creados            = 0;
    public $actualizados       = 0;
    public $fila               = 0;

    public function model(array $row)
    {
        $this->fila++;

        // 1) Categoría
        $categoria = Categoria::firstOrCreate([
            'nombre' => trim($row['categoria'])
        ]);
        if ($categoria->wasRecentlyCreated) {
            $this->categorias_creadas++;
        }

        // 2) Crear/actualizar Producto
        $producto = Producto::updateOrCreate(
            ['sku' => trim($row['codigo'])],
            [
                // <-- Aquí recuperas el 'NOMBRE' de tu Excel
                'nombre' => trim($row['nombre'])
                    ?: throw new \Exception("Fila {$this->fila}: falta NOMBRE"),
                // el resto de campos…
                'oem1'             => trim($row['oem1'] ?? ''),
                'oem2'             => trim($row['oem2'] ?? ''),
                'oem3'             => trim($row['oem3'] ?? ''),
                'oem4'             => trim($row['oem4'] ?? ''),
                'tipo_de_vehiculo' => trim($row['tipo_de_vehiculo'] ?? ''),
                'origen'           => trim($row['origen'] ?? ''),
                'ubicacion'        => trim($row['ubicacion'] ?? ''),
                'descripcion'      => trim($row['descripcion'] ?? ''),
                'imagen'           => trim($row['imagen'] ?? ''),
                'costo_yen'        => $row['costo_yen']   ?? 0,
                'costo_usd'        => $row['costo_usd']   ?? 0,
                'costo_clp'        => $row['costo_clp']   ?? 0,
                'precio'           => $row['precio']
                    ?? throw new Exception("Fila {$this->fila}: falta PRECIO"),
                'alto'             => $row['alto']        ?? null,
                'ancho'            => $row['ancho']       ?? null,
                'largo'            => $row['largo']       ?? null,
                'peso'             => $row['peso']        ?? null,
                'stock'            => $row['stock']       ?? 0,
                'categoria_id'     => $categoria->id,
            ]
        );


        $producto->wasRecentlyCreated
            ? $this->creados++
            : $this->actualizados++;

        // 3) Marcas (singular, pero puede traer varias separadas por coma)
        if (!empty($row['marca'])) {
            $ids = [];
            foreach (explode(',', $row['marca']) as $nombreMarca) {
                $m = Marca::firstOrCreate(['nombre' => trim($nombreMarca)]);
                if ($m->wasRecentlyCreated) $this->marcas_creadas++;
                $ids[] = $m->id;
            }
            $producto->marcas()->sync($ids);
        }

        // 4) Modelos (plural)
        if (!empty($row['modelos'])) {
            $ids = [];
            foreach (explode(',', $row['modelos']) as $nombreModelo) {
                $mo = Modelo::firstOrCreate(['nombre' => trim($nombreModelo)]);
                if ($mo->wasRecentlyCreated) $this->modelos_creados++;
                $ids[] = $mo->id;
            }
            $producto->modelos()->sync($ids);
        }

        // 5) Log de actividad
        activity()
            ->causedBy(Auth::user())
            ->performedOn($producto)
            ->withProperties(['attributes' => $producto->getAttributes()])
            ->event('imported_via_excel')
            ->log($producto->wasRecentlyCreated ? 'created' : 'updated');

        return $producto;
    }
}
