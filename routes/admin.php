<?php

use App\Http\Controllers\Admin\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\ProductosController;
use App\Http\Controllers\Admin\UsuariosController;
use App\Http\Controllers\Admin\VentasController;

// Rutas existentes...

// Rutas de recurso para Admin
Route::resource('categorias', CategoriasController::class)->names('admin.categorias');
Route::resource('productos', ProductosController::class)->names('admin.productos');
Route::get('importar/productos', [ProductosController::class, 'importar'])->name('admin.productos.importar');
Route::post('admin/importar/productos', [ProductosController::class, 'importSubmit'])->name('admin.productos.importar.submit');
Route::resource('usuarios', UsuariosController::class)->names('admin.usuarios');
Route::resource('ventas', VentasController::class)->names('admin.ventas');
Route::post('ventas/asignar-cliente', [VentasController::class, 'asignarClienteAjax'])->name('admin.ventas.asignarCliente');
Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/remove/{itemId}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/show', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/clear', [CartController::class, 'clearCart'])->name('cart.clear');
});
//Route::get('/',[HomeController::class, 'index'])->middleware('verified')->name('admin');
Route::get('/', function () {
    return view('admin.usuarios.index');
});
