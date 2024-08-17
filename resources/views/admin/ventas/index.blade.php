@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1>Administrar Ventas</h1> <button type="button" class="btn btn-primary" data-toggle="modal"
        data-target="#carritoModal">Ver
        Carrito</button>
@stop

@section('content')
    <table id="ventasTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Codigo de venta</th>
                <th>Estado</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Precio Total</th>
                <th>Fecha</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventas as $venta)
                <tr>
                    <td>{{ $venta->id }}</td>
                    <td
                        class="{{ $venta->estado == 'Pendiente'
                            ? 'table-warning'
                            : ($venta->estado == 'Cancelado'
                                ? 'table-danger'
                                : ($venta->estado == 'Devolucion'
                                    ? 'table-danger'
                                    : ($venta->estado == 'Completado'
                                        ? 'table-success'
                                        : ''))) }}">
                        {{ $venta->estado }}
                    </td>
                    <td>
                        @if ($venta->cliente)
                            {{ $venta->cliente->name }}
                        @else
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-primary" onclick="openModal({{ $venta->id }})">
                                Sin asignar
                            </button>
                        @endif
                    </td>
                    <td>{{ $venta->vendedor->name }}</td>
                    <td>{{ $venta->total * (1 + $empresa->impuestos / 100) }}</td>
                    <td>{{ $venta->updated_at }}</td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button"
                                id="dropdownMenuButton{{ $venta->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                Opciones
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $venta->id }}">
                                <li><a class="dropdown-item"
                                        href="{{ route('admin.ventas.show', $venta->id) }}">Detalle</a></li>
                                @if ($venta->estado !== 'Pendiente' && $venta->estado !== 'Completado' && $venta->estado !== 'Cancelado')
                                    <li><a class="dropdown-item" href="#"
                                            onclick="cambiarEstado({{ $venta->id }}, 'Pendiente')">A Pendiente</a></li>
                                @endif
                                @if ($venta->estado !== 'Completado' && $venta->estado !== 'Cancelado')
                                    <li><a class="dropdown-item" href="#"
                                            onclick="cambiarEstado({{ $venta->id }}, 'Completado')">A Completado</a>
                                    </li>
                                @endif
                                @if ($venta->estado == 'Pendiente' && $venta->estado !== 'Cancelado')
                                    <li><a class="dropdown-item" href="#"
                                            onclick="cambiarEstado({{ $venta->id }}, 'Cancelado')">A Cancelado</a></li>
                                @endif
                                @if ($venta->estado == 'Completado')
                                    <li><a class="dropdown-item" href="#"
                                            onclick="cambiarEstado({{ $venta->id }}, 'Devolucion')">A Devolución</a>
                                    </li>
                                @endif
                            </ul>
                            <script>
                                function cambiarEstado(ventaId, nuevoEstado) {
                                    const url = `{{ route('admin.ventas.cambiar-estado', ['venta' => 'ventaIdPlaceholder']) }}`.replace(
                                        'ventaIdPlaceholder', ventaId);

                                    fetch(url, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            },
                                            body: JSON.stringify({
                                                estado: nuevoEstado
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire({
                                                    title: 'Estado actualizado',
                                                    text: 'El estado de la venta ha sido actualizado correctamente.',
                                                    icon: 'success',
                                                    confirmButtonText: 'OK'
                                                }).then(() => {
                                                    location.reload(); // Recargar la página para reflejar los cambios
                                                });
                                            } else {
                                                Swal.fire({
                                                    title: 'Error',
                                                    text: 'Error al cambiar el estado.',
                                                    icon: 'error',
                                                    confirmButtonText: 'OK'
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            Swal.fire({
                                                title: 'Error',
                                                text: 'Error al cambiar el estado.',
                                                icon: 'error',
                                                confirmButtonText: 'OK'
                                            });
                                        });
                                }
                            </script>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="asignarClienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Asignar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="asignarClienteForm">
                        @csrf
                        <input type="hidden" id="ventaId" name="venta_id">
                        <!-- Campo oculto para el ID del cliente -->
                        <input type="hidden" id="cliente_id" name="cliente_id">
                        <div class="mb-3">
                            <label for="cliente_id_input" class="form-label">Selecciona un Cliente</label>
                            <input class="form-control" list="clientesList" id="cliente_id_input" autocomplete="off">
                            <datalist id="clientesList">
                                @foreach ($clientes as $cliente)
                                    <!-- Usa el nombre y correo como valor visible y almacena el ID en data-id -->
                                    <option value="{{ $cliente->name }} {{ $cliente->email }}"
                                        data-id="{{ $cliente->id }}"></option>
                                @endforeach
                            </datalist>
                        </div>
                        <button type="submit" class="btn btn-primary">Asignar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Carrito-->
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
                    <table id="carritoTable" class="display table w-100">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unidad</th>
                                <th>Subtotal</th>
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
                    <form action="{{ route('admin.ventas.store') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" id="concretarPropuesta"><i
                                class="fas fa-check-circle"></i> Concretar Propuesta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <style>
        .card-img-top {
            width: 100%;
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            background: #f3f3f3 url('ruta/a/tu/imagen/de/carga.gif') center center no-repeat;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .img-loaded {
            opacity: 1;
            /* Cambia a opacidad 1 cuando la imagen esté cargada */
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
                        Swal.fire('¡Éxito!', 'El carrito ha sido vaciado.', 'success');
                        $('#carritoModal').modal('hide');
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
                                    data: null,
                                    defaultContent: '<button class="btn btn-danger btn-sm eliminar-item">Eliminar</button>',
                                    orderable: false
                                },
                            ]
                        });
                    },
                    error: function(error) {
                        console.error('Error al cargar datos del carrito:', error);
                    }
                });

                // Manejar clic en botón eliminar
                $('#carritoTable').off('click', '.eliminar-item').on('click', '.eliminar-item', function() {
                    var row = $(this).closest('tr');
                    var data = $('#carritoTable').DataTable().row(row).data();
                    $.ajax({
                        url: '{{ route('cart.remove', '') }}/' + data.id,
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response, textStatus, xhr) {
                            if (xhr.status === 200) {
                                // Actualizar la tabla
                                $('#carritoTable').DataTable().row(row).remove().draw();
                                // Muestra un mensaje de éxito con SweetAlert
                                Swal.fire({
                                    title: 'Éxito',
                                    text: response.message,
                                    icon: 'success',
                                    confirmButtonText: 'Aceptar'
                                });
                            } else {
                                // Manejar otros códigos de estado HTTP como se desee
                                console.log(
                                    'Respuesta exitosa pero con código de estado:',
                                    xhr.status);
                            }
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            console.error('Error en la solicitud:', textStatus,
                                errorThrown);
                            // Muestra un mensaje de error con SweetAlert
                            Swal.fire({
                                title: 'Error',
                                text: 'Error al eliminar el ítem.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    });
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
        function openModal(ventaId) {
            $('#ventaId').val(ventaId);
            $('#asignarClienteModal').modal('show');
        }

        $(document).ready(function() {
            // Añade un listener al input para actualizar el campo oculto con el ID del cliente
            $('#cliente_id_input').on('input', function() {
                var valorSeleccionado = $(this).val();
                var opcionSeleccionada = $('#clientesList option').filter(function() {
                    return $(this).val() === valorSeleccionado;
                }).first();

                if (opcionSeleccionada && opcionSeleccionada.length > 0) {
                    var clienteId = opcionSeleccionada.data('id');
                    $('#cliente_id').val(
                        clienteId); // Asegúrate de que el ID del cliente se actualiza correctamente
                }
            });

            $('#asignarClienteForm').on('submit', function(event) {
                event.preventDefault();

                $.ajax({
                    url: '{{ route('admin.ventas.asignarCliente') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#asignarClienteModal').modal('hide');
                        Swal.fire({
                            title: 'Éxito!',
                            text: response.success,
                            icon: 'success',
                            confirmButtonText: 'Cool'
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Hubo un problema al asignar el cliente.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

            @if (session('success'))
                Swal.fire({
                    title: 'Éxito!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'Cool'
                })
            @endif
        });
    </script>
@stop
