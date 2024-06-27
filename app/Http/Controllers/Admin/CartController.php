<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $cart = Cache::get('cart', []);
        $item = $request->only(['id', 'nombre', 'cantodad', 'precio', 'costo']);
        $cart[$item['id']] = $item;
        Cache::put('cart', $cart);
        return response()->json(['message' => 'Producto añadido correctamente.']);
    }

    public function removeFromCart($itemId)
    {
        $cart = Cache::get('cart', []);
        if (isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Cache::put('cart', $cart);
            return response()->json(['message' => 'Producto removido correctamente.']);
        }
        return response()->json(['message' => 'Upps... no se encontro el producto.'], 404);
    }

    public function showCart()
    {
        $cart = Cache::get('cart', []);
        return response()->json(['cart' => $cart]);
    }

    public function clearCart()
    {
        Cache::forget('cart');
        return response()->json(['message' => 'Carrito limpiado.']);
    }
}
