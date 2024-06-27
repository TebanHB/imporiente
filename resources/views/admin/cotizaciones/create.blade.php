@extends('adminlte::page')

@section('title', 'Importar Productos')

@section('content_header')
    <h1>Crear una cotizacion
    </h1>
@stop
@section('content')
<div class="row">
    @foreach($productos as $producto)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="card" style="width: 18rem;">
                    <img src="{{asset('storage/productos/'.$producto->imagen.'.webp')}}" loading="lazy" class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 100) }}</p>
                        <a href="{{ route('admin.productos.show', $producto->id) }}" class="btn btn-primary">Ver más</a>
                        <a href="#" class="btn btn-success">Agregar al carrito</a>
                    </div>
                </div>
            </div>
@endforeach
</div>
@stop
@section('css')
<style>
    .card-img-top {
        width: 100%; /* Hace la imagen responsiva */
        max-width: 100%; /* Asegura que la imagen no sea más ancha que el contenedor */
        max-height: 200px; /* Altura máxima para todas las imágenes */
        object-fit: cover; /* Asegura que la imagen cubra el área sin perder su proporción */
    }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script></script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
