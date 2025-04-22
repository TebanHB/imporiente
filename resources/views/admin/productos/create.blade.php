@extends('adminlte::page')

@section('title', 'Crear Producto')

@section('content_header')
    <h1>Crear Producto</h1>
@stop

@section('content')
    <div class="container">
        {{-- 🚨 Mostrar mensajes de éxito/fracaso genéricos --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- 🚨 Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Hay algunos errores en el formulario:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('admin.productos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- SKU y Nombre --}}
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="sku">SKU <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku') }}"
                        required>
                </div>
                <div class="form-group col-md-6">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}"
                        required>
                </div>
            </div>

            {{-- OEMs --}}
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="oem1">OEM 1 <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="oem1" name="oem1" value="{{ old('oem1') }}"
                        required>
                </div>
                <div class="form-group col-md-3">
                    <label for="oem2">OEM 2</label>
                    <input type="text" class="form-control" id="oem2" name="oem2" value="{{ old('oem2') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="oem3">OEM 3</label>
                    <input type="text" class="form-control" id="oem3" name="oem3" value="{{ old('oem3') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="oem4">OEM 4</label>
                    <input type="text" class="form-control" id="oem4" name="oem4" value="{{ old('oem4') }}">
                </div>
            </div>

            {{-- Tipo de vehículo, Origen, Ubicación --}}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="tipo_de_vehiculo">Tipo de Vehículo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="tipo_de_vehiculo" name="tipo_de_vehiculo"
                        value="{{ old('tipo_de_vehiculo') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="origen">Origen <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="origen" name="origen" value="{{ old('origen') }}"
                        required>
                </div>
                <div class="form-group col-md-4">
                    <label for="ubicacion">Ubicación</label>
                    <input type="text" class="form-control" id="ubicacion" name="ubicacion"
                        value="{{ old('ubicacion') }}">
                </div>
            </div>

            {{-- Costos por moneda --}}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="costo_yen">Costo (¥) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="costo_yen" name="costo_yen"
                        value="{{ old('costo_yen') }}" min="0" step="0.01" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="costo_usd">Costo (USD) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="costo_usd" name="costo_usd"
                        value="{{ old('costo_usd') }}" min="0" step="0.01" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="costo_clp">Costo (CLP) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="costo_clp" name="costo_clp"
                        value="{{ old('costo_clp') }}" min="0" step="0.01" required>
                </div>
            </div>

            {{-- Precio y Stock --}}
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="precio">Precio <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="precio" name="precio"
                        value="{{ old('precio') }}" min="0" step="0.01" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="stock">Stock <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="stock" name="stock"
                        value="{{ old('stock') }}" min="0" step="1" required>
                </div>
            </div>

            {{-- Dimensiones --}}
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="alto">Alto</label>
                    <input type="number" class="form-control" id="alto" name="alto"
                        value="{{ old('alto') }}" min="0" step="0.01">
                </div>
                <div class="form-group col-md-3">
                    <label for="ancho">Ancho</label>
                    <input type="number" class="form-control" id="ancho" name="ancho"
                        value="{{ old('ancho') }}" min="0" step="0.01">
                </div>
                <div class="form-group col-md-3">
                    <label for="largo">Largo</label>
                    <input type="number" class="form-control" id="largo" name="largo"
                        value="{{ old('largo') }}" min="0" step="0.01">
                </div>
                <div class="form-group col-md-3">
                    <label for="peso">Peso</label>
                    <input type="number" class="form-control" id="peso" name="peso"
                        value="{{ old('peso') }}" min="0" step="0.01">
                </div>
            </div>

            {{-- Categoría --}}
            <div class="form-group">
                <label for="categoria_id">Categoría <span class="text-danger">*</span></label>
                <select class="form-control" id="categoria_id" name="categoria_id" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}"
                            {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Marcas --}}
            <div class="form-group">
                <label for="marcas">Marcas</label>
                <select class="form-control select2" id="marcas" name="marcas[]" multiple="multiple">
                    @foreach (\App\Models\Marca::all() as $marca)
                        <option value="{{ $marca->id }}"
                            {{ collect(old('marcas'))->contains($marca->id) ? 'selected' : '' }}>
                            {{ $marca->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Modelos --}}
            <div class="form-group">
                <label for="modelos">Modelos</label>
                <select class="form-control select2" id="modelos" name="modelos[]" multiple="multiple">
                    @foreach (\App\Models\Modelo::all() as $modelo)
                        <option value="{{ $modelo->id }}"
                            {{ collect(old('modelos'))->contains($modelo->id) ? 'selected' : '' }}>
                            {{ $modelo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Descripción --}}
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
            </div>

            {{-- Imagen --}}
            <div class="form-group">
                <label for="imagen">Imagen</label>
                <input type="file" class="form-control-file" id="imagen" name="imagen">
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
@stop

@section('css')
    {{-- Font Awesome (opcional) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    {{-- Select2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Selecciona una opción',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@stop
