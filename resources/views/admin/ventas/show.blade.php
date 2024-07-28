@extends('adminlte::page')

@section('title', 'Ventas')

@section('content_header')
    <h1>Codigo de venta: {{ $venta->id }}</h1>
@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container mb-5 mt-3">
                <div class="row d-flex align-items-baseline">
                    <div class="col-xl-9">
                        <p style="color: #7e8d9f;font-size: 20px;">Proforma<strong>: {{ $venta->id }}</strong></p>
                    </div>
                    <div class="col-xl-3 float-end">
                        <a data-mdb-ripple-init class="btn btn-light text-capitalize border-0"  data-mdb-ripple-color="dark"><i
                                class="fas fa-print text-primary"></i> Print</a>
                        <a href="{{ route('ventas.pdf', $venta->id) }}" data-mdb-ripple-init
                            class="btn btn-light text-capitalize" data-mdb-ripple-color="dark"><i
                                class="far fa-file-pdf text-danger"></i> Export</a>
                    </div>
                    <hr>
                </div>

                <div class="container">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h1 class="pt-0">{{ $empresa->nombre }}</h1>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-xl-8">
                            <ul class="list-unstyled">
                                <li class="text-muted">Para: <span style="color:#5d9fc5 ;">
                                        @if ($venta->cliente)
                                            {{ $venta->cliente->name }}
                                        @else
                                            Sin asignar
                                        @endif
                                    </span></li>
                                <li class="text-muted">{{ $empresa->calle }}, {{ $empresa->ciudad }}</li>
                                <li class="text-muted">{{ $empresa->pais }}</li>
                                <li class="text-muted"><i class="fas fa-phone"></i> {{ $empresa->numero }}</li>
                            </ul>
                        </div>
                        <div class="col-xl-4">
                            <p class="text-muted">Proforma</p>
                            <ul class="list-unstyled">
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                        class="fw-bold">Codigo de Venta:# </span>{{ $venta->id }}</li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                        class="fw-bold">Vendedor: </span>{{ $venta->vendedor->name }}</li>
                                <li class="text-muted"><i class="fas fa-circle" style="color:#84B0CA ;"></i> <span
                                        class="fw-bold">Fecha de creacion: </span>{{ $venta->created_at->format('d/m/Y') }}
                                </li>
                                <li class="text-muted"><i class="fas fa-circle"
                                        style="{{ match ($venta->estado) {
                                            default => 'color: #84B0CA;', // Color por defecto
                                        } }}"></i>
                                    <span class="me-1 fw-bold">Estado:</span><span class="badge"
                                        style="{{ match ($venta->estado) {
                                            'Cancelado' => 'background-color: #FFA500; color: black;', // Naranja
                                            'Pendiente' => 'background-color: #FFFF00; color: black;', // Amarillo
                                            'Completado' => 'background-color: #008000; color: white;', // Verde
                                            'Devolucion' => 'background-color: #FF0000; color: white;', // Rojo
                                            default => 'background-color: #84B0CA; color: black;', // Color por defecto
                                        } }} fw-bold">{{ $venta->estado }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="row my-2 mx-1 justify-content-center">
                        <table class="table table-striped table-borderless">
                            <thead style="background-color:#84B0CA ;" class="text-white">
                                <tr>
                                    <th scope="col">N°</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Cantidad</th>
                                    <th scope="col">Precio Unitario</th>
                                    <th scope="col">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venta->productos as $producto)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td><b>{{$producto->sku}} </b>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->pivot->cantidad }}</td>
                                        <td>${{ $producto->pivot->precio_venta }}</td>
                                        <!-- Modificado para usar precio_venta -->
                                        <td>${{ $producto->pivot->precio_venta * $producto->pivot->cantidad }}</td>
                                        <!-- Modificado para usar precio_venta -->
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="row">
                        <div class="col-xl-8">
                            <p class="ms-3"> <!-- AQUI DEBE DE IR ALGUN TEXTO/COMENTARIO--></p>
                        </div>
                        <div class="col-xl-3">
                            <ul class="list-unstyled">
                                @php
                                    $subtotal = 0;
                                    foreach ($venta->productos as $producto) {
                                        $subtotal += $producto->pivot->precio_venta * $producto->pivot->cantidad;
                                    }
                                    $taxRate = $empresa->impuestos / 100;
                                    $taxAmount = $subtotal * $taxRate; // Calcula el monto del impuesto
                                    $total = $subtotal + $taxAmount; // Calcula el total
                                @endphp

                                <li class="text-muted ms-3"><span class="text-black me-4">SubTotal:
                                    </span><b>${{ $subtotal }}</b></li>
                                <li class="text-muted ms-3 mt-2"><span
                                        class="text-black me-4">IVA({{ strpos((string) $empresa->impuestos, '.00') !== false ? intval($empresa->impuestos) : $empresa->impuestos }}%):
                                    </span><b>${{ $taxAmount }}</b></li>
                            </ul>
                            <p class="text-black float-start"><span class="text-black me-3"> Total: </span><span
                                    style="font-size: 25px;">${{ $total }}</span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-10">
                            <p>Gracias por tu preferencia</p>
                        </div>

                    </div>

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

@stop
