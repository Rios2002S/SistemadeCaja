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
<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success text-center" role="alert">
        Corte de caja realizado con éxito.
    </div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="alert alert-danger text-center" role="alert">
        Hubo un error al procesar el corte de caja. Inténtalo nuevamente.
    </div>
<?php endif; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Corte de Caja</h2>
                    <button class="home-btn" onclick="window.history.back()">
                        <i class="fas fa-home home-icon"></i>
                    </button><br>
                </div>
                <div class="card-body">
                    <p class="text-muted text-center">Revisa y confirma el corte de caja antes de proceder.</p>

                    <!-- Estadísticas Reales -->
                    <div class="row text-center mb-4">
                        <div class="col-md-6">
                            <h4 class="text-success">Total Vendido</h4>
                            <h3><span id="totalVendido">$<?= number_format($totalVendido, 2); ?></span></h3>
                        </div>
                        <div class="col-md-6">
                            <h4 class="text-danger">Retiros Anteriores</h4>
                            <h3><span id="retirosAnteriores">$<?= number_format($totalRetiros, 2); ?></span></h3>
                        </div>
                    </div>

                    <!-- Botón para abrir el modal de confirmación -->
                    <div class="text-center">
                        <button class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#modalConfirmacion">
                            Realizar Corte de Caja
                        </button>
                        <a href="retiros.php">                        
                            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalConfirmacion">
                            Ver retiros
                        </button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="modalConfirmacion" tabindex="-1" aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar Corte de Caja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>¿Estás seguro de realizar el corte de caja?</p>
                <p class="fw-bold">Esta acción no se puede deshacer.</p>

                <!-- Campo de Observaciones -->
                <div class="form-group">
                    <label for="observaciones">Observaciones:</label>
                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3" placeholder="Ejemplo: 'Se retiró todo el efectivo'"></textarea>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <form action="../bd/procesar_corte.php" method="POST">
                    <input type="hidden" name="total_vendido" value="<?= $totalVendido; ?>">
                    <input type="hidden" name="observaciones" id="observacionesForm"> <!-- Aquí pasamos el valor -->
                    <button type="submit" class="btn btn-danger">Confirmar</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Capturar el valor de las observaciones cuando se confirme el corte
document.querySelector('.btn-danger').addEventListener('click', function() {
    var observaciones = document.getElementById('observaciones').value;
    document.getElementById('observacionesForm').value = observaciones;
});

</script>

<?php
require_once '../headfooter/footer.php'; 
?>
