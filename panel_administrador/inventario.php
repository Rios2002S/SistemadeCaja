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
 
<div class="container mt-5">
    <h2 class="text-center mb-4">Gestión de Productos</h2>

    <!-- Botón para abrir el modal de agregar producto -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#modalAgregarProducto">
        Agregar Producto
    </button>
    <div class="container py-4">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <!-- Total de Productos -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-danger">
                        <h3><i class="fas fa-box"></i></h3>
                        <h5 class="card-title">Nº de Productos</h5>
                        <p class="display-6"><?= $total_productos ?></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-primary">
                        <h3><i class="fas fa-shopping-cart"></i></h3>
                        <h5 class="card-title">Total Productos</h5>
                        <p class="display-6"><?= $total_unidades?></p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-success bg-custom-dark">
                        <h3><i class="fas fa-money-bill-wave"></i></h3>
                        <h5 class="card-title">Dinero Vendido</h5>
                        <p class="display-6">$<?= $total_dinero_vendido ?></p>
                    </div>
                </div>
            </div>

            <!-- Producto Más Barato -->
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h3><i class="fas fa-tag"></i></h3>
                        <h5 class="card-title">Ventas Registradas</h5>
                        <p class="display-6"><?= $total_ventas ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once '../add/tablaproductos.php';
    ?>
    <!-- Modal para agregar producto -->
    <div class="modal fade" id="modalAgregarProducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="../bd/agregar_producto.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Precio</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad_en_stock" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="id_categoria" class="form-control">
                                <option value="">Sin categoría</option>
                                <?php
                                $categorias = $conn->query("SELECT * FROM categorias");
                                while ($cat = $categorias->fetch_assoc()) {
                                    echo "<option value='{$cat['id_categoria']}'>{$cat['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Imagen</label>
                            <input type="file" name="imagen" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Agregar</button>
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
