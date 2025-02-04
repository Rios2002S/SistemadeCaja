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
    <!-- Botón para generar el reporte en PDF -->
    <div class="container mt-4">
        <a href="../reportes/reporte_productos.php" class="btn btn-primary">Generar Reporte de todo</a>
        <a href="../reportes/reporte_productosescasos.php" class="btn btn-warning">Generar Reporte de Productos con Poco Stock</a>
        <a href="../reportes/reporte_productoterminado.php" class="btn btn-danger">Generar Reporte de Productos terminados</a>
    
        <button class="home-btn" onclick="window.history.back()">
            <i class="fas fa-home home-icon"></i>
        </button><br>
    </div>

    <h1>Lista de Productos</h1>
    <!-- Tabla con DataTables -->
    <table id="productosTable" class="display table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Precio</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($producto = $resultProductos->fetch_assoc()) { ?>
                <tr>
                    <td><?= $producto['id_producto'] ?></td>
                    <td><?= $producto['nombre'] ?></td>
                    <td>$<?= number_format($producto['precio'], 2) ?></td>
                    <td><?= $producto['cantidad_en_stock'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Iniciar DataTables -->
<script>
    $(document).ready( function () {
        $('#productosTable').DataTable({
            "paging": true,
            "searching": true,  // Habilitar búsqueda
            "ordering": true,   // Habilitar ordenamiento de columnas
            "lengthMenu": [5, 10, 15, 20], // Número de filas por página
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/Spanish.json"
            }
        });
    });
</script>

    
<?php
require_once '../headfooter/footer.php';
?>