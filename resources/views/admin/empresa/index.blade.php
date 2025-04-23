@extends('adminlte::page')

@section('title', isset($empresa) && $empresa->id ? 'Editar Empresa' : 'Registrar Empresa')

@section('content_header')
    <h1>{{ isset($empresa) && $empresa->id ? 'Editar Empresa' : 'Registrar Empresa' }}</h1>
@stop

@section('content')
    <form
        action="{{ isset($empresa) && $empresa->id ? route('admin.empresa.update', $empresa->id) : route('admin.empresa.store') }}"
        method="POST">
        @csrf
        @if (isset($empresa) && $empresa->id)
            @method('PUT')
        @endif

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                value="{{ old('nombre', isset($empresa) ? $empresa->nombre : '') }}">
        </div>

        <div class="form-group">
            <label for="pais">País</label>
            <input type="text" name="pais" id="pais" class="form-control"
                value="{{ old('pais', isset($empresa) ? $empresa->pais : '') }}">
        </div>

        <div class="form-group">
            <label for="numero">Teléfono</label>
            <input type="text" name="numero" id="numero" class="form-control"
                value="{{ old('numero', isset($empresa) ? $empresa->numero : '') }}">
        </div>

        <div class="form-group">
            <label for="ciudad">Ciudad</label>
            <input type="text" name="ciudad" id="ciudad" class="form-control"
                value="{{ old('ciudad', isset($empresa) ? $empresa->ciudad : '') }}">
        </div>

        <div class="form-group">
            <label for="estado">Región</label>
            <input type="text" name="estado" id="estado" class="form-control"
                value="{{ old('estado', isset($empresa) ? $empresa->estado : '') }}">
        </div>

        <div class="form-group">
            <label for="calle">Calle</label>
            <input type="text" name="calle" id="calle" class="form-control"
                value="{{ old('calle', isset($empresa) ? $empresa->calle : '') }}">
        </div>

        <div class="form-group">
            <label for="impuestos">Impuestos (%)</label>
            <input type="text" name="impuestos" id="impuestos" class="form-control"
                value="{{ old('impuestos', isset($empresa) ? $empresa->impuestos : '') }}">
        </div>
        <div class="form-group">
            <label for="ruat">Rut</label>
            <input type="text" name="ruat" id="ruat" class="form-control"
                value="{{ old('ruat', isset($empresa) ? $empresa->ruat : '') }}">
        </div>

        <button type="submit"
            class="btn btn-primary">{{ isset($empresa) && $empresa->id ? 'Guardar Cambios' : 'Registrar Empresa' }}</button>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script>
        console.log("Formulario de {{ isset($empresa) && $empresa->id ? 'edición' : 'registro' }} de empresa cargado.");
    </script>
@stop
