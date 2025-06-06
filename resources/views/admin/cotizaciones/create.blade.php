@extends('adminlte::page')

@section('title', 'Importar Productos')


@section('content_header')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Crear una cotizacion</a>
        <div class="collapse navbar-collapse" id="navbarSearch">
            <form class="form-inline my-2 my-lg-0 ml-auto">
                <input class="form-control mr-sm-2" type="search" placeholder="Codigo imporiente" aria-label="Search"
                    list="Codigo" id="codigoSearch">
                <datalist id="Codigo">
                    @foreach ($productos as $producto)
                        @if ($producto->sku)
                            <option value="{{ $producto->sku }}">
                        @endif
                        @if ($producto->oem1)
                            <option value="{{ $producto->oem1 }}">
                        @endif
                        @if ($producto->oem2)
                            <option value="{{ $producto->oem2 }}">
                        @endif
                        @if ($producto->oem3)
                            <option value="{{ $producto->oem3 }}">
                        @endif
                        @if ($producto->oem4)
                            <option value="{{ $producto->oem4 }}">
                        @endif
                    @endforeach
                </datalist>

                <input class="form-control mr-sm-2" type="search" placeholder="Buscar articulo" aria-label="Search"
                    list="Nombre" id="nombreSearch">
                <datalist id="Nombre">
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->nombre }}">
                    @endforeach
                </datalist>

                <input class="form-control mr-sm-2" type="search" placeholder="Buscar Categoria" aria-label="Search"
                    list="Categoria" id="categoriaSearch">
                <datalist id="Categoria">
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->nombre }}">
                    @endforeach
                </datalist>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#carritoModal">Ver
                    Carrito</button>
            </form>
        </div>
    </nav>
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
                    <img src="{{ asset('storage/' . $producto->imagen) }}" loading="lazy" class="card-img-top"
                        alt="{{ $producto->nombre }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $producto->nombre }}</h5>
                        <p class="card-text">{{ Str::limit($producto->descripcion, 100) }}
                            <br>Categoria: {{ $producto->categoria->nombre }}
                            <br>Precio: ${{ $producto->precio }}
                            <br>Stock: {{ $producto->stock }}
                            <br>Marcas: @foreach ($producto->marcas as $marca)
                                <span>{{ $marca->nombre }}</span>{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        </p>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
@stop

@section('js')
    <script>
        const productos = @json($productos);

        document.addEventListener("DOMContentLoaded", function() {
            const codigoSearch = document.getElementById('codigoSearch');
            const nombreSearch = document.getElementById('nombreSearch');
            const categoriaSearch = document.getElementById('categoriaSearch');

            // Event listeners para los inputs de búsqueda
            codigoSearch.addEventListener('input', filterProducts);
            nombreSearch.addEventListener('input', filterProducts);
            categoriaSearch.addEventListener('input', filterProducts);

            function filterProducts() {
                const codigo = codigoSearch.value.toLowerCase();
                const nombre = nombreSearch.value.toLowerCase();
                const categoria = categoriaSearch.value.toLowerCase();

                const filteredProducts = productos.filter(producto => {
                    return (
                        (codigo === '' || producto.sku.toLowerCase().includes(codigo) ||
                            producto.oem1?.toLowerCase().includes(codigo) ||
                            producto.oem2?.toLowerCase().includes(codigo) ||
                            producto.oem3?.toLowerCase().includes(codigo) ||
                            producto.oem4?.toLowerCase().includes(codigo)) &&
                        (nombre === '' || producto.nombre.toLowerCase().includes(nombre)) &&
                        (categoria === '' || producto.categoria.nombre.toLowerCase().includes(
                            categoria))
                    );
                });

                renderProducts(filteredProducts);
            }

            function renderProducts(products) {
                const productsContainer = document.querySelector('.row');
                productsContainer.innerHTML = '';

                products.forEach(producto => {
                    const productCard = `
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="card" style="width: 18rem;">
                            <img src="{{ asset('storage/${producto.imagen}') }}" loading="lazy"
                                class="card-img-top img-loaded" alt="${producto.nombre}">
                            <div class="card-body">
                                <h5 class="card-title">${producto.nombre}</h5>
                                <p class="card-text">${producto.descripcion.substr(0, 100)}
                                    <br>Categoria: ${producto.categoria.nombre}
                                    <br>Precio: $${producto.precio}
                                    <br>Stock: ${producto.stock}
                                    <br>Marcas: ${producto.marcas.map(marca => `<span>${marca.nombre}</span>`).join(', ')}
                                </p>
                                <a href="{{ url('/admin/productos/show/${producto.id}') }}" class="btn btn-primary">Ver más</a>
                                <a href="#" class="btn btn-success addToCartBtn" data-id="${producto.id}"
                                    data-price="${producto.precio}" data-name="${producto.nombre}">Agregar al carrito</a>
                            </div>
                        </div>
                    </div>
                `;
                    productsContainer.insertAdjacentHTML('beforeend', productCard);
                });

                // Añadir la clase 'img-loaded' a las imágenes que se carguen correctamente
                var images = document.querySelectorAll('.card-img-top');
                images.forEach(function(img) {
                    if (img.complete) {
                        img.classList.add('img-loaded');
                    } else {
                        img.addEventListener('load', function() {
                            img.classList.add('img-loaded');
                        });
                    }
                });
            }

            // Inicializar el renderizado con todos los productos
            renderProducts(productos);

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
        });
    </script>
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
@stop
