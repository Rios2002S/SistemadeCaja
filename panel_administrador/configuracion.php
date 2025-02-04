<?php
ob_start(); // Iniciar el buffer de salida
require_once '../headfooter/head.php';
require_once '../bd/consultas/consultas.php';
// Si no es administrador, redirigirlo a una página de acceso denegado
if ($es_admin != 1) {
    header("Location: ../vendedor/no_acceso.php"); // Puedes cambiar la URL según tu necesidad
    exit();
}
ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>

<div class="container mt-4">
    <h1 class="text-center">Gestión de Usuarios y Sucursales</h1>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="usuarios-tab" data-bs-toggle="tab" href="#usuarios" role="tab" aria-controls="usuarios" aria-selected="true">Usuarios</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="sucursales-tab" data-bs-toggle="tab" href="#sucursales" role="tab" aria-controls="sucursales" aria-selected="false">Sucursales</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <!-- Tab de Usuarios -->
        <div class="tab-pane fade show active" id="usuarios" role="tabpanel" aria-labelledby="usuarios-tab">
            <div class="container mt-4">
                <!-- Botón para abrir modal de registro de usuario -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarUsuario">
                    <i class="fas fa-user-plus"></i> Agregar Usuario
                </button>

                <?php
                require_once '../add/tablausuarios.php'; // Mostrar la tabla de usuarios
                ?>
            </div>
        </div>

        <!-- Tab de Sucursales -->
        <div class="tab-pane fade" id="sucursales" role="tabpanel" aria-labelledby="sucursales-tab">
            <div class="container mt-4">
                <!-- Botón para abrir modal de registro de sucursal -->
                <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarSucursal">
                    <i class="fas fa-store"></i> Agregar Sucursal
                </button>

                <?php
                require_once '../add/tablasucursales.php'; // Mostrar la tabla de sucursales
                ?>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="modalAgregarUsuario" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../bd/adduser.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre de Usuario</label>
                            <input type="text" name="nombreusu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="contrausu" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Clave de Administrador</label>
                            <input type="password" name="contrasena" class="form-control" required>
                        </div>
                        <!-- Sucursal -->
                        <div class="mb-3">
                            <label for="sucursal_asignada" class="form-label">Sucursal</label>
                            <select name="sucursal_asignada" class="form-select" id="sucursal_asignada" required>
                                <option value="">Seleccione la sucursal que tendrá a cargo</option>
                                <?php if ($resultSucursales->num_rows > 0) { 
                                    // Mostrar las sucursales desde la base de datos
                                    while ($row = $resultSucursales->fetch_assoc()) {
                                        echo "<option value='" . $row['nombre_sucursal'] . "'>" . $row['nombre_sucursal'] . "</option>";
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Sucursal -->
    <div class="modal fade" id="modalAgregarSucursal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../bd/add_sucursal.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Sucursal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre de Sucursal</label>
                            <input type="text" name="nombre_sucursal" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Dirección</label>
                            <input type="text" name="direccion" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" name="num_tel" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Registrar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php
require_once '../headfooter/footer.php';
?>
