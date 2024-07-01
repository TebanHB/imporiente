@extends('adminlte::page')

@section('title', 'Importar Productos')


@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Crear una cotización</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#carritoModal">Ver
            Carrito</button>
    </div>
@stop
@section('content')
    <!-- Modal para Ver Carrito -->
    <div class="modal fade" id="carritoModal" tabindex="-1" role="dialog" aria-labelledby="carritoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="carritoModalLabel">Carrito de Compras</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="carritoTable" class="display">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unidad</th>
                                <th>Subtotal</th>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se llenarán los datos dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
                        Cerrar</button>
                    <button type="button" class="btn btn-danger" id="vaciarCarrito"><i class="fas fa-trash"></i> Vaciar
                        Carrito</button>
                    <button type="button" class="btn btn-primary" id="concretarPropuesta"><i
                            class="fas fa-check-circle"></i> Concretar Propuesta</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal agregar la carrito-->

    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel"><b> Agregar al Carrito:</b> <span id="productName"
                            class="float-right"></span></h5>
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
                            <label for="precio_venta_unidad">Precio de venta:</label>
                            <input type="number" class="form-control" id="precio_venta_unidad" name="precio_venta_unidad"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                        </div>
                        <input type="hidden" id="producto_id" name="producto_id">
                        <button type="submit" class="btn btn-primary">Agregar al carrito</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach ($productos as $producto)
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="card" style="width: 18rem;">
                    <img src="{{ asset('storage/productos/' . $producto->imagen . '.jpg') }}" loading="lazy"
                        class="card-img-top" alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 100) }}</p>
                        <a href="{{ route('admin.productos.show', $producto->id) }}" class="btn btn-primary">Ver más</a>
                        <a href="#" class="btn btn-success addToCartBtn" data-id="{{ $producto->id }}"
                            data-price="{{ $producto->precio }}" data-name="{{ $producto->nombre }}">Agregar al
                            carrito</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
@section('css')
    <style>
        .card-img-top {
            width: 100%;
            /* Hace la imagen responsiva */
            max-width: 100%;
            /* Asegura que la imagen no sea más ancha que el contenedor */
            max-height: 200px;
            /* Altura máxima para todas las imágenes */
            object-fit: cover;
            /* Asegura que la imagen cubra el área sin perder su proporción */
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {

            $('#vaciarCarrito').click(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('cart.clear') }}", // Ruta para vaciar el carrito
                    data: {
                        _token: $("meta[name='csrf-token']").attr(
                            "content") // Token CSRF necesario para peticiones POST en Laravel
                    },
                    success: function(response) {
                        // Aquí puedes recargar los datos del carrito para reflejar que ahora está vacío
                        // o actualizar la interfaz de usuario según sea necesario
                        Swal.fire('¡Éxito!', 'El carrito ha sido vaciado.', 'success')
                    },
                    error: function(error) {
                        // Manejo de errores
                        Swal.fire('Error', 'No se pudo vaciar el carrito.', 'error');
                    }
                });
            });





            $('#carritoModal').on('show.bs.modal', function() {
                $.ajax({
                    url: '{{ route('cart.show') }}',
                    method: 'GET',
                    success: function(data) {
                        // Limpia la tabla antes de llenarla
                        $('#carritoTable').DataTable().clear().destroy();

                        // Llenar la tabla
                        $('#carritoTable').DataTable({
                            data: data.cart,
                            columns: [{
                                    data: 'nombre_producto'
                                },
                                {
                                    data: 'cantidad'
                                },
                                {
                                    data: 'precio_venta_unidad'
                                },
                                {
                                    data: 'subtotal'
                                },
                                {
                                    data: 'nombre_vendedor'
                                },
                                {
                                    data: 'created_at',
                                    render: function(data) {
                                        return new Date(data)
                                            .toLocaleDateString() + ' ' +
                                            new Date(data).toLocaleTimeString();
                                    }
                                }
                            ]
                        });
                    },
                    error: function(error) {
                        // Manejo de errores
                        console.error('Error al cargar datos del carrito:', error);
                    }
                });
            });
            //script anterior
            $('.addToCartBtn').click(function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var productPrice = $(this).data('price');
                var productName = $(this).data('name'); // Asume que el botón tiene un atributo data-name
                $('#producto_id').val(productId);
                $('#suggestedPrice').text(productPrice);
                $('#cantidad').val(1); // Establece la cantidad en 1 (puedes cambiar esto si lo deseas
                $('#productName').text(productName); // Actualiza el nombre del producto en el modal
                $('#precio_venta_unidad').val(
                    productPrice); // Establece el precio sugerido como valor inicial del precio de venta
                $('#cartModal').modal('show');
            });

            $('#addToCartForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serializeArray(); // Cambia a serializeArray para manipular los datos
                var urlVentasStore = "{{ route('cart.add') }}";
                formData.push({
                    name: "_token",
                    value: $("meta[name='csrf-token']").attr("content")
                }); // Añade el token CSRF
                $.ajax({
                    type: "POST",
                    url: urlVentasStore, // Cambia esto por la ruta a tu controlador
                    data: formData,
                    success: function(response) {
                        $('#cartModal').modal('hide');
                        // Aquí puedes agregar una notificación de éxito
                        Swal.fire('¡Éxito!', 'Producto agregado al carrito', 'success');
                    },
                    error: function(error) {
                        // Manejo de errores
                        //Swal.fire('Error', 'No se pudo agregar el producto al carrito',
                        //'error');
                        var errorMessage = error.responseJSON && error.responseJSON.message ?
                            error.responseJSON.message :
                            'No se pudo agregar el producto al carrito';
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
