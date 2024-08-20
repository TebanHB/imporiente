<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresa = Empresa::first();
        if ($empresa) {
            return $this->edit($empresa->id);
        } else {
            return $this->create();
        }
    }

    public function create()
    {
        //$empresa = new Empresa(); // Crear una nueva instancia vacía
        return view('admin.empresa.index');
    }

    public function store(Request $request)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'nombre' => 'required',
            'ruat' => 'required',
            'pais' => 'required',
            'numero' => 'required',
            'impuestos' => 'required',
            'calle' => 'required',
        ]);
        // Crear la empresa
        try {
            Empresa::create($request->all());
            return redirect()->route('empresa.index')->with('success', 'Empresa creada exitosamente.');
        } catch (\Exception $e) {
            // Registrar el error en los logs
            Log::error('Error al crear la empresa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear la empresa.');
        }
    }

    public function show(Empresa $empresa)
    {
        return view('empresa.show', compact('empresa'));
    }

    public function edit($id)
    {
        $empresa = Empresa::find($id);
        return view('admin.empresa.index', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        // Validar los datos de la solicitud
        $request->validate([
            'nombre' => 'required',
            'ruat' => 'required',
            'pais' => 'required',
            'numero' => 'required',
        ]);
    
        try {
            // Intentar actualizar la empresa
            $empresa->update($request->all());
            return redirect()->route('empresa.index')->with('success', 'Empresa actualizada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Manejar errores de la base de datos
            Log::error('Error al actualizar la empresa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al actualizar la empresa en la base de datos.');
        } catch (\Exception $e) {
            // Manejar cualquier otro tipo de error
            Log::error('Error inesperado al actualizar la empresa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error inesperado al actualizar la empresa.');
        }
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();
        return redirect()->route('empresa.index')->with('success', 'Empresa eliminada exitosamente.');
    }

    // Métodos para roles
    public function roles()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente.');
    }

    public function editRole(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Rol actualizado exitosamente.');
    }

    public function destroyRole(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol eliminado exitosamente.');
    }

    // Métodos para permisos
    public function permisos()
    {
        $permisos = Permission::all();
        return view('permisos.index', compact('permisos'));
    }

    public function storePermiso(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create($request->all());
        return redirect()->route('permisos.index')->with('success', 'Permiso creado exitosamente.');
    }

    public function editPermiso(Permission $permiso)
    {
        return view('permisos.edit', compact('permiso'));
    }

    public function updatePermiso(Request $request, Permission $permiso)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permiso->id,
        ]);

        $permiso->update($request->all());
        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado exitosamente.');
    }

    public function destroyPermiso(Permission $permiso)
    {
        $permiso->delete();
        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado exitosamente.');
    }
}