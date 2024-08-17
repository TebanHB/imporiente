@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="sku">SKU<b>*</b></label>
                    <input type="text" class="form-control" id="sku" name="sku" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nombre">Nombre<b>*</b></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="oem1">OEM 1</label>
                    <input type="text" class="form-control" id="oem1" name="oem1">
                </div>
                <div class="form-group col-md-3">
                    <label for="oem2">OEM 2</label>
                    <input type="text" class="form-control" id="oem2" name="oem2">
                </div>
                <div class="form-group col-md-3">
                    <label for="oem3">OEM 3</label>
                    <input type="text" class="form-control" id="oem3" name="oem3">
                </div>
                <div class="form-group col-md-3">
                    <label for="oem4">OEM 4</label>
                    <input type="text" class="form-control" id="oem4" name="oem4">
                </div>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="costo">Costo<b>*</b></label>
                    <input type="number" min="0" step="1" class="form-control" id="costo" name="costo"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="precio">Precio<b>*</b></label>
                    <input type="number" min="0" step="1" class="form-control" id="precio" name="precio"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="stock">Stock<b>*</b></label>
                    <input type="number" min="0" step="1" class="form-control" id="stock" name="stock"
                        required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="alto">Alto</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="alto" name="alto">
                </div>
                <div class="form-group col-md-3">
                    <label for="ancho">Ancho</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="ancho" name="ancho">
                </div>
                <div class="form-group col-md-3">
                    <label for="largo">Largo</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="largo"
                        name="largo">
                </div>
                <div class="form-group col-md-3">
                    <label for="peso">Peso</label>
                    <input type="number" min="0" step="0.01" class="form-control" id="peso"
                        name="peso">
                </div>
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select class="form-control" id="categoria_id" name="categoria_id" required>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@stop
