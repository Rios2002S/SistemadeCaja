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
    <h3 class="text-center mb-4">Historial de Retiros</h3>
    
    <button class="home-btn" onclick="window.history.back()">
        <i class="fas fa-home home-icon"></i>
    </button><br>

    <!-- Botón para generar reporte -->
    <div class="text-center mb-4">
        <form action="../reportes/reporte_retiros.php" method="POST">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>
        </form>
    </div>

    <!-- Tabla de Retiros -->
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Monto</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($retiro = $resultadoRetiros->fetch_assoc()) { ?>
                <tr>
                    <td><?= $retiro['id']; ?></td>
                    <td>$<?= number_format($retiro['monto'], 2); ?></td>
                    <td><?= $retiro['fecha']; ?></td>
                    <td><?= $retiro['nombreusu']; ?></td> <!-- Nombre del usuario -->
                    <td><?= $retiro['observaciones']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php
require_once '../headfooter/footer.php'; 
?>
