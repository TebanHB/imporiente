<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\ProductoVenta;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes =User::role('cliente')->get();
        if (auth()->user()->hasRole('admin')) {
            // El usuario es un administrador, mostrar todas las ventas incluyendo los datos del vendedor y del cliente
            $ventas = Venta::with(['vendedor', 'cliente', 'productos'])->get()->map(function ($venta) {
                $total = $venta->productos->reduce(function ($carry, $producto) {
                    return $carry + ($producto->pivot->cantidad * $producto->pivot->precio_venta);
                }, 0);
                $venta->total = $total;
                return $venta;
            });
        } else {
            // El usuario no es un administrador, mostrar solo sus ventas incluyendo los datos del cliente
            $ventas = Venta::with(['vendedor', 'cliente', 'productos'])->where('vendedor_id', auth()->id())->get()->map(function ($venta) {
                $total = $venta->productos->reduce(function ($carry, $producto) {
                    return $carry + ($producto->pivot->cantidad * $producto->pivot->precio_venta);
                }, 0);
                $venta->total = $total;
                return $venta;
            });
        }

        return view('admin.ventas.index', compact('ventas', 'clientes'));
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
        /*$productosArray = DB::select("SELECT * FROM productos");
        $productos = collect($productosArray)->map(function ($producto) {
            return (object)$producto;
        });*/
        $productos = Producto::orderBy('id', 'desc')->get();
        return view('admin.cotizaciones.create', compact('productos'));
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
            return redirect()->route('admin.ventas.index')->with('success', 'Venta creada con éxito');
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
