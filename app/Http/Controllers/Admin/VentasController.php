<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\ProductoVenta;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Log;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = User::role('cliente')->get();
        if (auth()->user()->hasRole('admin')) {
            // El usuario es un administrador, mostrar todas las ventas incluyendo los datos del vendedor y del cliente
            $ventas = Venta::with(['vendedor', 'cliente', 'productos'])
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($venta) {
                    $total = $venta->productos->reduce(function ($carry, $producto) {
                        return $carry + ($producto->pivot->cantidad * $producto->pivot->precio_venta);
                    }, 0);
                    $venta->total = $total;
                    return $venta;
                });
        } else {
            // El usuario no es un administrador, mostrar solo sus ventas incluyendo los datos del cliente
            $ventas = Venta::with(['vendedor', 'cliente', 'productos'])
                ->where('vendedor_id', auth()->id())
                ->orderBy('id', 'desc')
                ->get()
                ->map(function ($venta) {
                    $total = $venta->productos->reduce(function ($carry, $producto) {
                        return $carry + ($producto->pivot->cantidad * $producto->pivot->precio_venta);
                    }, 0);
                    $venta->total = $total;
                    return $venta;
                });
        }
        $empresa = Empresa::first();
        return view('admin.ventas.index', compact('ventas', 'clientes', 'empresa'));
    }

    public function cambiarEstado(Request $request, $ventaId)
    {
        $venta = Venta::findOrFail($ventaId);
        $venta->estado = $request->input('estado');

        if ($venta->estado === 'Completado') {
            foreach ($venta->productos as $producto) {
                $cantidadVendida = $producto->pivot->cantidad;

                // Asegurarse de que el stock no sea negativo
                $producto->stock = $producto->stock - $cantidadVendida;

                $producto->save();
            }
        }

        $venta->save();

        return response()->json(['success' => true]);
    }

    public function asignarClienteAjax(Request $request)
    {
        $venta = Venta::find($request->venta_id);
        $venta->cliente_id = $request->cliente_id;
        $venta->save();

        return response()->json(['success' => 'Cliente asignado correctamente.']);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::orderBy('id', 'desc')->get();
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('admin.cotizaciones.create', compact('productos', 'marcas', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Paso 1: Recuperar ítems del carrito
            $carritoItems = Carrito::where('vendedor_id', auth()->id())->get();

            // Paso 2: Crear una nueva venta
            $venta = Venta::create([
                'fecha_venta' => now(), // O cualquier otra lógica para establecer la fecha
                'expiracion_oferta' => null, // Ajustar según sea necesario
                'cliente_id' => null, // Asumiendo que el cliente es el usuario autenticado
                'vendedor_id' => auth()->id(),
                'estado' => 'Pendiente', // Ajustar según sea necesario
            ]);

            // Paso 3: Para cada ítem en el carrito
            foreach ($carritoItems as $item) {
                ProductoVenta::create([
                    'producto_id' => $item->producto_id,
                    'venta_id' => $venta->id,
                    'cantidad' => $item->cantidad,
                    'precio_venta' => $item->precio_venta_unidad,
                ]);

                // Actualizar stock del producto (si es necesario)
                $producto = Producto::find($item->producto_id);
                $producto->stock -= $item->cantidad;
                $producto->save();
            }

            // Paso 4: Eliminar ítems del carrito (opcional)
            Carrito::where('vendedor_id', auth()->id())->delete();

            DB::commit();
            return redirect()->route('admin.ventas.index')->with('success', 'Venta #' . $venta->id . ' creada con éxito');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al realizar la venta'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $empresa = Empresa::first();
        $venta = Venta::with(['vendedor', 'cliente', 'productos'])->find($id);
        return view('admin.ventas.show', compact('venta', 'empresa'));
    }

    public function generarPDF($ventaId)
    {
        try {
            Log::info('Iniciando la generación del PDF para la venta ID: ' . $ventaId);
    
            $venta = Venta::with(['vendedor', 'cliente', 'productos'])->findOrFail($ventaId);
            $empresa = Empresa::first();
    
            // Crear la carpeta node-scripts si no existe
            $nodeScriptsPath = base_path('app/Http/node-scripts');
            if (!file_exists($nodeScriptsPath)) {
                mkdir($nodeScriptsPath, 0777, true);
                Log::info('Carpeta node-scripts creada.');
            }
    
            // Renderizar la vista HTML y guardarla en un archivo
            $htmlContent = view('admin.ventas.pdf', compact('venta', 'empresa'))->render();
            $htmlFilePath = $nodeScriptsPath . '/vista.html';
            file_put_contents($htmlFilePath, $htmlContent);
            Log::info('Archivo HTML creado en: ' . $htmlFilePath);
    
            // Verificar si el archivo HTML se creó correctamente
            if (!file_exists($htmlFilePath)) {
                throw new \Exception('No se pudo crear el archivo HTML.');
            }
    
            // Copiar el archivo CSS a la carpeta de scripts
            $cssFilePath = $nodeScriptsPath . '/invoice.css';
            copy(public_path('css/pdf/invoice.css'), $cssFilePath);
            Log::info('Archivo CSS copiado a: ' . $cssFilePath);
    
            // Verificar si el archivo CSS se copió correctamente
            if (!file_exists($cssFilePath)) {
                throw new \Exception('No se pudo copiar el archivo CSS.');
            }
    
            // Generar un nombre de archivo único basado en el ID de la factura
            $pdfFileName = 'factura' . $ventaId . '.pdf';
            $pdfFilePath = $nodeScriptsPath . '/' . $pdfFileName;
    
            // Eliminar el archivo si ya existe
            if (file_exists($pdfFilePath)) {
                unlink($pdfFilePath);
                Log::info('Archivo PDF existente eliminado: ' . $pdfFilePath);
            }
    
            // Ejecutar el script de Node.js para generar el PDF
            $output = shell_exec('node ' . $nodeScriptsPath . '/generate-pdf.cjs ' . $ventaId . ' ' . $pdfFilePath . ' 2>&1');
            Log::info('Salida del comando shell_exec: ' . $output);
    
            // Verificar si el archivo PDF se creó correctamente
            if (!file_exists($pdfFilePath)) {
                throw new \Exception('No se pudo generar el archivo PDF.');
            }
            Log::info('Archivo PDF creado en: ' . $pdfFilePath);
    
            // Descargar el PDF generado
            $response = response()->download($pdfFilePath);
    
            // Eliminar archivos temporales después de la descarga
            $response->deleteFileAfterSend(true);
    
            Log::info('PDF generado para la venta ID: ' . $ventaId);
    
            return $response;
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF para la venta ID: ' . $ventaId . ' - ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el PDF: ' . $e->getMessage()], 500);
        } finally {
            // Eliminar archivos temporales
            if (file_exists($htmlFilePath)) {
                unlink($htmlFilePath);
            }
            if (file_exists($cssFilePath)) {
                unlink($cssFilePath);
            }
            Log::info('Archivos temporales eliminados.');
        }
    }









    /* public function generarPDF($ventaId)
{
    $venta = Venta::with(['vendedor', 'cliente', 'productos'])->findOrFail($ventaId);

    $total = $venta->productos->reduce(function ($carry, $producto) {
        return $carry + ($producto->pivot->cantidad * $producto->pivot->precio_venta);
    }, 0);
    $venta->total = $total;
    $empresa = Empresa::first();
    $pdf = FacadePdf::loadView('admin.ventas.pdf', compact('venta', 'empresa'));
    
    return view('admin.ventas.pdf', compact('venta', 'empresa'));
    //return $pdf->download('venta_' . $venta->id . '.pdf');
}*/
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
