<?php
require_once '../bd/cn.php'; // Conexión a la base de datos

// Verificar que se ha enviado el ID de la venta a eliminar
if (!isset($_GET['id_venta']) || !is_numeric($_GET['id_venta'])) {
    echo json_encode(['success' => false, 'message' => 'ID de venta no válido.']);
    exit;
}

// Obtener el ID de la venta desde el parámetro GET
$id_venta = $_GET['id_venta'];

// Iniciar transacción
$conn->begin_transaction();

try {
    // Primero, obtener los detalles de la venta para restaurar el stock de los productos
    $stmtDetalles = $conn->prepare("SELECT id_producto, cantidad FROM detalleventas WHERE id_venta = ?");
    $stmtDetalles->bind_param("i", $id_venta);
    $stmtDetalles->execute();
    $resultDetalles = $stmtDetalles->get_result();

    while ($detalle = $resultDetalles->fetch_assoc()) {
        // Restaurar el stock de cada producto
        $stmtStock = $conn->prepare("UPDATE productos SET cantidad_en_stock = cantidad_en_stock + ? WHERE id_producto = ?");
        $stmtStock->bind_param("ii", $detalle['cantidad'], $detalle['id_producto']);
        $stmtStock->execute();
    }

    // Ahora, eliminar los detalles de la venta
    $stmtEliminarDetalles = $conn->prepare("DELETE FROM detalleventas WHERE id_venta = ?");
    $stmtEliminarDetalles->bind_param("i", $id_venta);
    $stmtEliminarDetalles->execute();

    // Finalmente, eliminar la venta
    $stmtEliminarVenta = $conn->prepare("DELETE FROM ventas WHERE id_venta = ?");
    $stmtEliminarVenta->bind_param("i", $id_venta);
    $stmtEliminarVenta->execute();

    // Confirmar la transacción
    $conn->commit();

    // Responder con éxito
    echo json_encode(['success' => true, 'message' => 'Venta eliminada correctamente.']);
} catch (Exception $e) {
    // Si ocurre una excepción, hacer rollback
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la venta: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
