<?php
ob_start(); // Iniciar el buffer de salida
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../headfooter/head.php';
require_once '../bd/consultas/consultas.php';
require_once '../add/tarjetas.php';

// Si no es administrador, redirigirlo a una página de acceso denegado
if ($es_admin != 1) {
    header("Location: ../vendedor/no_acceso.php"); // Puedes cambiar la URL según tu necesidad
    exit();
}
ob_end_flush(); // Terminar el buffer de salida y enviar todo
?>

    <div class="container">
        <div class="row mt-2">
            <!-- Columna para la gráfica -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos Vendidos</h5>
                        <canvas id="graficaPastel" width="400" height="400"></canvas>
                    </div>
                </div>
            </div>

            <!-- Columna para la tabla -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Productos con Menor Stock</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($producto = $resultProductosMenorStock->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?= $producto['nombre'] ?></td>
                                        <td><?= $producto['cantidad_en_stock'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><br>

    <script>
        // Obtener los datos de los productos vendidos de PHP
        var productos = <?php 
            $productosArray = [];
            while ($producto = $resultProductosVendidos->fetch_assoc()) {
                $productosArray[] = [
                    'nombre' => $producto['nombre'],
                    'cantidad_vendida' => $producto['cantidad_vendida']
                ];
            }
            echo json_encode($productosArray);
        ?>;
        
        // Obtener las etiquetas (nombres de los productos) y los valores (cantidades vendidas)
        var etiquetas = productos.map(function(producto) {
            return producto.nombre;
        });
        
        var cantidades = productos.map(function(producto) {
            return producto.cantidad_vendida;
        });
        
        // Crear la gráfica de pastel
        var ctx = document.getElementById('graficaPastel').getContext('2d');
        var graficaPastel = new Chart(ctx, {
            type: 'pie', // Tipo de gráfica: pastel
            data: {
                labels: etiquetas, // Etiquetas (nombres de los productos)
                datasets: [{
                    data: cantidades, // Datos (cantidades vendidas)
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Colores del pastel
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' unidades';
                            }
                        }
                    }
                }
            }
        });
    </script>

    <div class="container">
            <div
                class="alert alert-warning"
                role="alert"
            >               
    </div>
    
    
<?php
require_once '../headfooter/footer.php';
?>