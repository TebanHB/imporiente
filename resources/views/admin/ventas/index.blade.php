@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1>Administrar Ventas</h1>
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
                        class="{{ $venta->estado == 'pendiente'
                            ? 'table-warning'
                            : ($venta->estado == 'cancelado'
                                ? 'table-danger'
                                : ($venta->estado == 'devolucion'
                                    ? 'table-danger'
                                    : ($venta->estado == 'vendido'
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
                    <td>{{ $venta->total }}</td>
                    <td>{{ $venta->updated_at }}</td>
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
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
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
