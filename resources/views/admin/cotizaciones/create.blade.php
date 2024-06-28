@extends('adminlte::page')

@section('title', 'Importar Productos')

@section('content_header')
    <h1>Crear una cotizacion
    </h1>
@stop
@section('content')
<!-- Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartModalLabel"><b> Agregar al Carrito:</b> <span id="productName" class="float-right"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addToCartForm">
                    <div class="form-group">
                        <label>Precio sugerido: <span id="suggestedPrice">Precio</span></label>
                    </div>
                    <div class="form-group">
                        <label for="salePrice">Precio de venta:</label>
                        <input type="number" class="form-control" id="salePrice" name="salePrice" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Cantidad:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>
                    <input type="hidden" id="productId" name="productId">
                    <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @foreach($productos as $producto)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="card" style="width: 18rem;">
                    <img src="{{asset('storage/productos/'.$producto->imagen.'.webp')}}" loading="lazy" class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 100) }}</p>
                        <a href="{{ route('admin.productos.show', $producto->id) }}" class="btn btn-primary">Ver más</a>
                        <a href="#" class="btn btn-success addToCartBtn" data-id="{{ $producto->id }}" data-price="{{ $producto->precio }}" data-name="{{ $producto->nombre }}">Agregar al carrito</a>
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
    <script>
    $(document).ready(function() {
        $('.addToCartBtn').click(function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            var productPrice = $(this).data('price');
            var productName = $(this).data('name'); // Asume que el botón tiene un atributo data-name
            $('#productId').val(productId);
            $('#suggestedPrice').text(productPrice);
            $('#productName').text(productName); // Actualiza el nombre del producto en el modal
            $('#cartModal').modal('show');
        });
    
        $('#addToCartForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize(); // Captura los datos del formulario
            $.ajax({
                type: "POST",
                url: "/ruta/a/tu/controlador", // Cambia esto por la ruta a tu controlador
                data: formData,
                success: function(response) {
                    $('#cartModal').modal('hide');
                    // Aquí puedes agregar una notificación de éxito
                    alert("Producto agregado al carrito");
                },
                error: function(error) {
                    // Manejo de errores
                    alert("Error al agregar el producto al carrito");
                }
            });
        });
    });
    </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
