<!-- Tabla Sucursales -->
<div class="table-responsive">
    <table id="sucursalesTable" class="display table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Sucursal</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($sucursal = $resultDatosSucursales->fetch_assoc()) { ?>
                <tr>
                    <td><?= $sucursal['id_sucursal'] ?></td>
                    <td><?= htmlspecialchars($sucursal['nombre_sucursal']) ?></td>
                    <td><?= htmlspecialchars($sucursal['direccion']) ?></td>
                    <td><?= htmlspecialchars($sucursal['num_tel']) ?></td>
                    <td>
                        <!-- Botón para editar -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarSucursal<?= $sucursal['id_sucursal'] ?>">
                            <i class="fas fa-cogs"></i>
                        </button>
                        <!-- Botón para eliminar -->
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminarSucursal<?= $sucursal['id_sucursal'] ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar Sucursal -->
                <div class="modal fade" id="modalEditarSucursal<?= $sucursal['id_sucursal'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="../bd/updatesucursal.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Sucursal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_sucursal" value="<?= $sucursal['id_sucursal'] ?>">
                                    <div class="mb-3">
                                        <label>Nombre de Sucursal</label>
                                        <input type="text" name="nombre_sucursal" class="form-control" value="<?= htmlspecialchars($sucursal['nombre_sucursal']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Dirección</label>
                                        <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($sucursal['direccion']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Teléfono</label>
                                        <input type="text" name="num_tel" class="form-control" value="<?= htmlspecialchars($sucursal['num_tel']) ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Sucursal -->
                <div class="modal fade" id="modalEliminarSucursal<?= $sucursal['id_sucursal'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="../bd/deletesucursal.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminar Sucursal</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_sucursal" value="<?= $sucursal['id_sucursal'] ?>">
                                    <p>¿Estás seguro de que deseas eliminar la sucursal <strong><?= htmlspecialchars($sucursal['nombre_sucursal']) ?></strong>?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </tbody>
    </table>
</div>
<!-- Inicializar DataTables -->
<script>
    $(document).ready(function () {
        $('#sucursalesTable').DataTable({
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
