<?php
// Incluir el archivo de conexión a la base de datos
require_once('../bd/consultas/consultas.php');

// Obtener el ID de la venta desde el parámetro GET
$id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : 0;

// Consulta para obtener los detalles de la venta
$sqlDetallesVenta = "
    SELECT p.nombre AS producto, dv.cantidad, dv.precio, dv.subtotal
    FROM detalleventas dv
    JOIN productos p ON dv.id_producto = p.id_producto
    WHERE dv.id_venta = $id_venta
";

// Ejecutar la consulta
$resultDetallesVenta = $conn->query($sqlDetallesVenta);

// Generar la tabla con los detalles de los productos
if ($resultDetallesVenta->num_rows > 0) {
    echo "<table class='table table-bordered'>
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>";

    while ($detalle = $resultDetallesVenta->fetch_assoc()) {
        echo "<tr>
                <td>" . $detalle['producto'] . "</td>
                <td>" . $detalle['cantidad'] . "</td>
                <td>$" . number_format($detalle['precio'], 2) . "</td>
                <td>$" . number_format($detalle['subtotal'], 2) . "</td>
              </tr>";
    }

    echo "</tbody></table>";
} else {
    echo "No se encontraron detalles para esta venta.";
}
?>
