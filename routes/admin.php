<?php

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
Route::resource('usuarios', UsuariosController::class)->names('admin.usuarios');
Route::resource('ventas', VentasController::class)->names('admin.ventas');
//Route::get('/',[HomeController::class, 'index'])->middleware('verified')->name('admin');
Route::get('/', function () {
    return view('admin.usuarios.index');
});
?>