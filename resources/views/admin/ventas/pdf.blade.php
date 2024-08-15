<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pdf/invoice.css') }}">

</head>
<body>
    <div class="invoice-wrapper" id="print-area">
        <div class="invoice">
            <div class="invoice-container">
                <!-- Incluye aquí el contenido de la factura con las variables Blade -->
                <div class="invoice-head">
                    <div class="invoice-head-top">
                        <div class="invoice-head-top-left text-start">
                            <img src="{{ asset('AdminLTELogo.ico') }}">
                        </div>
                        <div class="invoice-head-top-right text-end">
                            <h3>Invoice</h3>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div class="invoice-head-middle">
                        <div class="invoice-head-middle-left text-start">
                            <p><span class="text-bold">Fecha</span>: {{ $venta->updated_at->format('d/m/Y') }}</p>
                        </div>
                        <div class="invoice-head-middle-right text-end">
                            <p><span class="text-bold">Proforma #:</span>{{ $venta->id }}</p>
                        </div>
                    </div>
                    <div class="hr"></div>
                    <div class="invoice-head-bottom">
                        <div class="invoice-head-bottom-left">
                            <ul>
                                <li class="text-bold">Invoiced To:</li>
                                <li>{{ $venta->cliente ? $venta->cliente->name : 'Sin asignar' }}</li>
                                <li>{{ $empresa->calle }}, {{ $empresa->ciudad }}</li>
                                <li>{{ $empresa->pais }}</li>
                            </ul>
                        </div>
                        <div class="invoice-head-bottom-right">
                            <ul class="text-end">
                                <li class="text-bold">Pay To:</li>
                                <li>{{ $empresa->nombre }}</li>
                                <li>{{ $empresa->calle }}</li>
                                <li>{{ $empresa->ciudad }}</li>
                                <li>{{ $empresa->pais }}</li>
                                <li>{{ $empresa->numero }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="overflow-view">
                    <div class="invoice-body">
                        <table>
                            <thead>
                                <tr>
                                    <td class="text-bold">N°</td>
                                    <td class="text-bold">Descripcion</td>
                                    <td class="text-bold">Cantidad</td>
                                    <td class="text-bold">Precio Unitario</td>
                                    <td class="text-bold">Monto</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venta->productos as $producto)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><b>{{ $producto->sku }} </b>{{ $producto->nombre }}</td>
                                        <td>{{ $producto->pivot->cantidad }}</td>
                                        <td>${{ $producto->pivot->precio_venta }}</td>
                                        <td class="text-end">${{ $producto->pivot->precio_venta * $producto->pivot->cantidad }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @php
                        $subtotal = 0;
                        foreach ($venta->productos as $producto) {
                            $subtotal += $producto->pivot->precio_venta * $producto->pivot->cantidad;
                        }
                        $taxRate = $empresa->impuestos / 100;
                        $taxAmount = $subtotal * $taxRate; // Calcula el monto del impuesto
                        $total = $subtotal + $taxAmount; // Calcula el total
                    @endphp
                        <div class="invoice-body-bottom">
                            <div class="invoice-body-info-inline">
                                <div class="info-item-td text-end text-bold">Sub Total:</div>
                                <div class="info-item-td text-end">${{ $subtotal }}</div>
                            </div>
                            <div class="invoice-body-info-inline">
                                <div class="info-item-td text-end text-bold">Tax:</div>
                                <div class="info-item-td text-end">${{ $taxAmount }}</div>
                            </div>
                            <div class="invoice-body-info-inline">
                                <div class="info-item-td text-end text-bold">Total:</div>
                                <div class="info-item-td text-end">${{ $total }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
