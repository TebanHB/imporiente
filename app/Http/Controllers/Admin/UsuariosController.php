<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UsuariosController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
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
        $roles = [];
        if (auth()->user()->can('asignar roles y permisos')) {
            $roles = Role::all();
        }
        return view('admin.usuarios.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'telefono' => 'nullable|string|max:255',
                'estado' => 'required|boolean',
                'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $password = Str::random(10);
    
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
                'telefono' => $request->telefono,
                'estado' => $request->estado,
                'imagen' => $request->file('imagen') ? $request->file('imagen')->store('images', 'public') : null,
            ]);
    
            if (auth()->user()->can('asignar roles y permisos')) {
                if ($request->has('roles') && is_array($request->roles)) {
                    $user->syncRoles($request->roles);
                }
            } else {
                $user->assignRole('cliente');
            }
    
            // Enviar notificación con la contraseña generada
            try {
                $this->notificationService->sendEmail($user->email, 'Tu nueva cuenta ha sido creada', [
                    'user' => $user,
                    'password' => $password,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with([
                    'status' => 'error',
                    'message' => 'Usuario creado, pero ocurrió un error al enviar el correo: ' . $e->getMessage()
                ]);
            }
    
            return redirect()->route('admin.usuarios.index')->with([
                'status' => 'success',
                'message' => 'Usuario creado exitosamente y la contraseña ha sido enviada por correo electrónico.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ]);
        }
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
        return redirect()->route('admin.usuarios.index')->with('status', 'success')->with('message', 'Usuario ' . $usuario->name . ' activado exitosamente.');
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
            return redirect()->route('admin.usuarios.index')->with('status', 'success')->with('message', 'Usuario ' . $usuario->name . ' fue desactivado correctamente.');
        } else {
            // Redirigir con un mensaje de error si el usuario no existe
            return redirect()->route('admin.usuarios.index')->with('status', 'error')->with('message', 'Usuario no encontrado.');
        }
    }
}
