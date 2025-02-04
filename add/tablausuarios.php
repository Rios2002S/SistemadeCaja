    <!-- Tabla Usuarios -->
<div class="table-responsive">
    <table id="usuariosTable" class="display table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de Usuario</th>
                <th>Rol</th>
                <th>Sucursal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($usuario = $resultUsuarios->fetch_assoc()) { ?>
                <tr>
                    <td><?= $usuario['id_usuario'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombreusu']) ?></td>
                    <td><?= $usuario['es_admin'] ? 'Administrador' : 'Usuario Regular' ?></td>
                    <td><?= htmlspecialchars($usuario['sucursal_asignada'] ?: 'No asignada') ?></td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $usuario['id_usuario'] ?>">
                            <i class="fas fa-cogs"></i>
                        </button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminarUsuario<?= $usuario['id_usuario'] ?>">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar Usuario -->
                <div class="modal fade" id="modalEditarUsuario<?= $usuario['id_usuario'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="../bd/updateuser.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editar Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                    <div class="mb-3">
                                        <label>Nombre de Usuario</label>
                                        <input type="text" name="nombreusu" class="form-control" value="<?= htmlspecialchars($usuario['nombreusu']) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Rol</label>
                                        <select name="es_admin" class="form-control">
                                            <option value="1" <?= $usuario['es_admin'] ? 'selected' : '' ?>>Administrador</option>
                                            <option value="0" <?= !$usuario['es_admin'] ? 'selected' : '' ?>>Usuario Regular</option>
                                        </select>
                                    </div>
                                    <!-- Sucursal -->
                                    <div class="mb-3">
                                        <label for="update-sucursal_asignada" class="form-label">Sucursal</label>
                                        <select name="sucursal_asignada" id="update-sucursal_asignada" class="form-select" required>
                                            <?php
                                            // Mostrar la sucursal asignada si existe
                                            if (isset($usuario['sucursal_asignada']) && !empty($usuario['sucursal_asignada'])) {
                                                echo "<option value='" . htmlspecialchars($usuario['sucursal_asignada']) . "' selected>" . htmlspecialchars($usuario['sucursal_asignada']) . "</option>";
                                            } else {
                                                echo "<option value=''>Sucursal no asignada</option>"; // Opción por defecto si no hay sucursal
                                            }
                                            ?>
                                            
                                            <?php
                                            // Verifica si $resultSucursales tiene filas
                                            if ($resultSucursalesEditU->num_rows > 0) {
                                                // Resetea el puntero al inicio del resultado
                                                $resultSucursalesEditU->data_seek(0);

                                                // Recorrer las sucursales y omitir la sucursal asignada
                                                while ($row_sucursal = $resultSucursalesEditU->fetch_assoc()) {
                                                    // Verificar si la sucursal ya está asignada al usuario
                                                    if ($row_sucursal['nombre_sucursal'] != $usuario['sucursal_asignada']) {
                                                        echo "<option value='" . htmlspecialchars($row_sucursal['nombre_sucursal']) . "'>" . htmlspecialchars($row_sucursal['nombre_sucursal']) . "</option>";
                                                    }
                                                }
                                            } else {
                                                echo "<option value=''>No hay sucursales disponibles</option>"; // Opción por defecto si no hay sucursales
                                            }
                                            ?>
                                        </select>
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

                <!-- Modal Eliminar Usuario -->
                <div class="modal fade" id="modalEliminarUsuario<?= $usuario['id_usuario'] ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="../bd/deleteuser.php" method="POST">
                                <div class="modal-header">
                                    <h5 class="modal-title">Eliminar Usuario</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                    <p>¿Estás seguro de que deseas eliminar al usuario <strong><?= htmlspecialchars($usuario['nombreusu']) ?></strong>?</p>
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
        $('#usuariosTable').DataTable({
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