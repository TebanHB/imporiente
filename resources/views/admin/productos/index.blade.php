@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Productos</h1>
@stop

@section('content')
        <table id="productos-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Costo</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $producto)
                    <tr>
                        <td>{{ $producto->id }}</td>
                        <td>{{ $producto->sku }}</td>
                        <td>{{ $producto->nombre }}</td>
                        <td>{{ $producto->descripcion }}</td>
                        <td>
                            @if($producto->imagen)
                                <img loading="lazy" src="{{ asset('storage/productos/' . $producto->imagen . '.jpg') }}" alt="Imagen no encontrada o con otra extencion" width="100" height="100">
                            @else
                                No Image
                            @endif
                        </td>
                        <td>{{ $producto->costo }}</td>
                        <td>{{ $producto->precio }}</td>
                        <td>{{ $producto->stock }}</td>
                        <td>{{ $producto->categoria->nombre }}</td>
                        <td>
                            <a href="{{ route('admin.productos.edit', $producto->id) }}" class="btn btn-sm btn-primary">Editar</a>
                            <button class="btn btn-sm btn-success addToCartBtn" data-id="{{ $producto->id }}" data-price="{{ $producto->precio }}" data-name="{{ $producto->nombre }}">Agregar al carrito</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    <!-- Modal agregar al carrito -->
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
                            <label for="precio_venta_unidad">Precio de venta:</label>
                            <input type="number" class="form-control" id="precio_venta_unidad" name="precio_venta_unidad" required>
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
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#productos-table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
                }
            });

            // Delegación de eventos para el botón "Agregar al carrito"
            $(document).on('click', '.addToCartBtn', function(e) {
                e.preventDefault();
                var productId = $(this).data('id');
                var productPrice = $(this).data('price');
                var productName = $(this).data('name');

                $('#producto_id').val(productId);
                $('#suggestedPrice').text(productPrice);
                $('#cantidad').val(1);
                $('#productName').text(productName);
                $('#precio_venta_unidad').val(productPrice);

                $('#cartModal').modal('show');
            });

            $('#addToCartForm').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serializeArray();
                formData.push({
                    name: "_token",
                    value: $("meta[name='csrf-token']").attr("content")
                });

                $.ajax({
                    type: "POST",
                    url: "{{ route('cart.add') }}",
                    data: formData,
                    success: function(response) {
                        $('#cartModal').modal('hide');
                        Swal.fire('¡Éxito!', 'Producto agregado al carrito', 'success');
                    },
                    error: function(error) {
                        var errorMessage = error.responseJSON && error.responseJSON.message ? error.responseJSON.message : 'No se pudo agregar el producto al carrito';
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            });
        });
    </script>
@stop