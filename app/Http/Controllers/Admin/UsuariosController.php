<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
    /**
     * Una lista de los usuarios.
     */
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }
    /**
     * Lista de los clientes.
     */
    public function clientes()
    {
        $usuarios = User::role('cliente')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }
    /**
     * Lista de los trabajadores.
     */
    public function trabajadores()
    {
        $usuarios = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin')
                ->orWhere('name', 'trabajador')
                ->orWhere('name', 'vendedor');
        })->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }
    /**
     * Muestra el formulario para crear.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.usuarios.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.usuarios.create', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function activate($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->estado = 1;
        $usuario->save();
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario activado exitosamente.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el usuario por su ID
        $usuario = User::find($id);

        // Verificar si el usuario existe
        if ($usuario) {
            // Cambiar el estado del usuario a 0
            $usuario->estado = 0;

            // Guardar los cambios
            $usuario->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('admin.usuarios.index')->with('status', 'success')->with('message', 'Usuario desactivado correctamente.');
        } else {
            // Redirigir con un mensaje de error si el usuario no existe
            return redirect()->route('admin.usuarios.index')->with('status', 'error')->with('message', 'Usuario no encontrado.');
        }
    }
}
