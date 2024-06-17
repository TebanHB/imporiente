@extends('adminlte::page')

@section('title', 'Importar Productos')

@section('content_header')
    <h1><i class="fas fa-fw fa-file-excel" style="color: green;"></i> Importar productos desde <b style="color:green">Excel</b>
    </h1>
@stop

@section('content')
    {{-- Modal de Éxito --}}
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Importación Exitosa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Productos y categorías importados con éxito.
                    <ul>
                        <li>Categorías creadas: {{ session('categoriasCreadas') }}</li>
                        <li>Productos creados: {{ session('productosCreados') }}</li>
                        <li>Productos actualizados: {{ session('productosActualizados') }}</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de Error --}}
    <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Error en la Importación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Hubo un error al importar los productos y categorías: {{ session('error') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de Importación --}}
    <form action="{{ route('admin.productos.importar.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Selecciona el archivo Excel para importar productos:</label>
            <input type="file" class="form-control" id="file" name="file" required>
        </div>
        <button type="submit" class="btn btn-success">Importar</button>
    </form>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Mostrar el modal de éxito si hay un mensaje de éxito
            @if (session('success'))
                $('#successModal').modal('show');
            @endif

            // Mostrar el modal de error si hay un mensaje de error
            @if (session('error'))
                $('#errorModal').modal('show');
            @endif
        });
    </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
