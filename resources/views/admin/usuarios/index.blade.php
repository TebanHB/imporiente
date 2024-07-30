```blade
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <nav class="navbar navbar-light bg-light justify-content-between">
        <p class="navbar-brand">Administracion de usuarios</p>
        <form class="form-inline">
            <button class="btn btn-outline-success" type="button">Registrar usuario</button>
        </form>
    </nav>
@stop

@section('content')
    <table id="usuarios" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
                <tr class="{{ $usuario->estado == 0 ? 'text-muted' : '' }}">
                    <td>{{ $usuario->id }}</td>
                    <td class="{{ $usuario->estado == 0 ? 'text-decoration-line-through' : '' }}">{{ $usuario->name }}</td>
                    <td class="{{ $usuario->estado == 0 ? 'text-decoration-line-through' : '' }}">{{ $usuario->email }}</td>
                    <td class="{{ $usuario->estado == 0 ? 'text-decoration-line-through' : '' }}">
                        {{ $usuario->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <!-- Aquí puedes agregar botones de acción como editar o eliminar -->
                        <a href="{{ route('admin.usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-sm">Editar</a>
                        <form action="{{ $usuario->estado == 0 ? route('admin.usuarios.activate', $usuario->id) : route('admin.usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @if($usuario->estado == 0)
                                <button type="submit" class="btn btn-success btn-sm">Activar</button>
                            @else
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            @endif
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <style>
        .text-decoration-line-through {
            text-decoration: line-through;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('status'))
                Swal.fire({
                    icon: '{{ session('status') }}', // 'success' or 'error'
                    title: '{{ session('status') === 'success' ? 'Éxito' : 'Error' }}',
                    text: '{{ session('message') }}',
                });
            @endif

            // Inicializar DataTables con búsqueda por columna
            var table = $('#usuarios').DataTable();
        });
    </script>
@stop
