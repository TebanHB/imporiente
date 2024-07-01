<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrito;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $item = $request->only(['producto_id', 'cantidad', 'precio_venta_unidad']);
        $carritoItem = Carrito::where('producto_id', $item['producto_id'])
                                ->where('vendedor_id', auth()->id())
                                ->first();

        if ($carritoItem) {
            // Opción 1: Incrementa la cantidad (y actualiza el precio si es necesario)
            $carritoItem->cantidad += $item['cantidad'];
            // Si deseas actualizar el precio también, descomenta la siguiente línea
            //$carritoItem->precio_venta_unidad = $item['precio_venta_unidad'];
            $carritoItem->save();
        } else {
            $item['vendedor_id'] = auth()->id();
            // Si el producto no existe, lo añade al carrito
            Carrito::create($item);
        }

        return response()->json(['message' => 'Producto añadido correctamente.']);
    }

    public function removeFromCart($productoId)
    {
        $carritoItem = Carrito::where('producto_id', $productoId)->first();

        if ($carritoItem) {
            $carritoItem->delete();
            return response()->json(['message' => 'Producto removido correctamente.']);
        }

        return response()->json(['message' => 'Upps... no se encontró el producto.'], 404);
    }

    public function showCart()
    {
        $cart = Carrito::join('productos', 'carrito.producto_id', '=', 'productos.id')
            ->leftJoin('users as clie', 'clie.id', '=', 'carrito.cliente_id')
            ->leftJoin('users as vend', 'vend.id', '=', 'carrito.vendedor_id')
            ->where('vendedor_id', auth()->id())
            ->select('carrito.*', 
                     'productos.nombre as nombre_producto', 
                     'clie.name as nombre_cliente', 
                     'vend.name as nombre_vendedor', 
                     DB::raw('carrito.cantidad * carrito.precio_venta_unidad as subtotal'))
            ->get();
        return response()->json(['cart' => $cart]);
    }

    public function getCartItemCount()
    {
        $totalCount = Carrito::sum('cantidad');
        return response()->json(['count' => $totalCount]);
    }

    public function clearCart()
    {
        Carrito::where('vendedor_id', auth()->id())->delete();
        return response()->json(['message' => 'Carrito limpiado.']);
    }
}
