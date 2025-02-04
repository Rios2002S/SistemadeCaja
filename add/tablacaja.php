<div class="container mt-5">
    <div class="row">
        <!-- Caja (columna de la izquierda) -->
        <div class="col-md-4">
            <h4>Caja</h4>  
            <table class="table table-bordered" id="carrito">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se llenará dinámicamente -->
                </tbody>
            </table>
            <h5 class="text-end">Total: $<span id="total">0.00</span></h5>
            <div id="pagoFields" style="display: none;">
                <div class="mb-3">
                    <label for="pago" class="form-label">Monto Pagado</label>
                    <input type="number" class="form-control" id="pago" placeholder="Monto pagado" min="0" required>
                </div>
                <div class="mb-3">
                    <label for="vuelto" class="form-label">Vuelto</label>
                    <input type="text" class="form-control" id="vuelto" disabled>
                </div>
            </div>
            <button class="btn btn-primary w-100" id="procesarVenta" hidden>Procesar Venta</button>
        </div>

        <!-- Productos Disponibles (columna de la derecha) -->
        <div class="col-md-8">
            <h4>Productos Disponibles</h4>
            <div class="table-responsive">
                <table class="table table-bordered" id="tablacaja">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock Disponible</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="productosTable">
                        <?php while ($row = $resultadoCaja->fetch_assoc()) { ?>
                            <tr class="producto-item" data-nombre='<?= strtolower($row['nombre']) ?>' data-descripcion='<?= strtolower($row['descripcion']) ?>'>
                                <td><?= $row['id_producto'] ?></td>
                                <td><?= $row['nombre'] ?></td>
                                <td><?= $row['descripcion'] ?></td>
                                <td>$<?= number_format($row['precio'], 2) ?></td>
                                <td><?= $row['cantidad_en_stock'] ?></td>
                                <td>
                                    <button class="btn btn-success btn-sm add-to-cart" 
                                            data-id="<?= $row['id_producto'] ?>" 
                                            data-nombre="<?= $row['nombre'] ?>" 
                                            data-precio="<?= $row['precio'] ?>"
                                            data-stock="<?= $row['cantidad_en_stock'] ?>">
                                        Agregar
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tablacaja').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "lengthMenu": [5, 10, 20, 50],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/Spanish.json"
            }
        });
    });
</script>
