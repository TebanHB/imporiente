<!-- Modal Structure -->
<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Carrito de Compras</h3>
                    <!-- Correct the data-target to match the modal's ID -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cartModal">Agregar Producto</button>
                </div>
                <div class="card-body">
                    <table id="cartTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Aquí se insertarán los productos del carrito mediante JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
        $('#cartTable').DataTable({
                // Opciones de configuración de DataTables
                "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
                }
        });
});
</script>
@endpush