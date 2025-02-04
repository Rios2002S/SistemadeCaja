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
    
    <!-- Botón para generar el reporte en PDF -->
    <div class="container mt-4">
        <a href="../reportes/reporte_ventas.php" class="btn btn-success">Generar Reporte Ventas</a>
    
        <button class="home-btn" onclick="window.history.back()">
            <i class="fas fa-home home-icon"></i>
        </button><br>
    </div>

    <h2>Ventas Realizadas</h2>
    <table id="ventasTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Vendedor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($venta = $resultVentas->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $venta['id_venta']; ?></td>
                    <td><?php echo $venta['fecha_venta']; ?></td>
                    <td>$<?php echo number_format($venta['total'], 2); ?></td>
                    <td><?php echo $venta['vendedor']; ?></td>
                    <td>
                        <!-- Botón para abrir el modal de detalles -->
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalDetalles" onclick="verDetalles(<?php echo $venta['id_venta']; ?>)">Ver detalles</button>
                        <!-- Botón para eliminar la venta -->
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminacion(<?php echo $venta['id_venta']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta venta?</p>
                    <div id="mensajeError" class="text-danger"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarEliminacionBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para ver los detalles de la venta -->
    <div class="modal fade" id="modalDetalles" tabindex="-1" aria-labelledby="modalDetallesLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesLabel">Detalles de la Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoDetalles">
                    <!-- Los detalles de la venta se cargarán aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar los detalles de la venta
        function verDetalles(idVenta) {
            // Usar AJAX para obtener los detalles de los productos de esa venta
            $.ajax({
                url: 'obtener_detalles_venta.php', // Archivo que procesará la solicitud
                type: 'GET',
                data: { id_venta: idVenta },
                success: function(response) {
                    // Mostrar los detalles en el modal
                    $('#contenidoDetalles').html(response);
                    $('#modalDetalles').modal('show');
                }
            });
        }
    </script>
    <!-- Script para eliminar la venta con AJAX -->
    <script>
        let ventaId = null;

        function confirmarEliminacion(id) {
            ventaId = id;
            // Muestra el modal de confirmación
            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

        // Confirmar la eliminación
        document.getElementById('confirmarEliminacionBtn').addEventListener('click', function() {
            if (ventaId !== null) {
                // Realizamos la solicitud AJAX para eliminar la venta
                fetch(`eliminar_venta.php?id_venta=${ventaId}`, {
                    method: 'GET',
                })
                .then(response => response.json())
                .then(data => {
                    // Mostrar mensaje según el resultado
                    if (data.success) {
                        document.getElementById('mensajeError').textContent = 'Venta eliminada correctamente.';
                        document.getElementById('mensajeError').classList.remove('text-danger');
                        document.getElementById('mensajeError').classList.add('text-success');

                        // Ocultar el modal después de un corto tiempo
                        setTimeout(() => {
                            // Recargar la página para actualizar la lista
                            location.reload();
                        });
                    } else {
                        document.getElementById('mensajeError').textContent = data.message;
                        document.getElementById('mensajeError').classList.add('text-danger');
                    }
                })
                .catch(error => {
                    document.getElementById('mensajeError').textContent = 'Hubo un error al eliminar la venta.';
                    document.getElementById('mensajeError').classList.add('text-danger');
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#ventasTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json" // Para idioma español
                },
                "pageLength": 5,  // Número de filas por página
                "lengthMenu": [5, 10, 15, 20],  // Opciones de filas por página
                "ordering": false,  // Habilitar ordenamiento de columnas
                "searching": true, // Habilitar búsqueda
                "paging": true,    // Habilitar paginación
                "info": true       // Mostrar información de la tabla (número de filas)
            });
        });
    </script>
<?php
require_once '../headfooter/footer.php';
?>

