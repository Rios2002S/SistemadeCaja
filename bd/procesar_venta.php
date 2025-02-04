<?php
session_start(); // Inicia la sesión

// Verificar que el id_usuario esté en la sesión (esto implica que el usuario esté autenticado)
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit; // Detener el script si no está autenticado
}

// Obtener el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];  // Este es el id del usuario logueado

// Conexión a la base de datos
require_once 'cn.php';

// Obtener los datos del carrito que se enviaron por POST
$carrito = json_decode(file_get_contents('php://input'), true);

// Verificar si el carrito está vacío
if (empty($carrito)) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío.']);
    exit;
}

// Calcular el total de la venta
$totalVenta = 0;
foreach ($carrito as $producto) {
    // Verificar que los datos necesarios están presentes en cada producto
    if (!isset($producto['id'], $producto['precio'], $producto['cantidad'], $producto['subtotal'])) {
        echo json_encode(['success' => false, 'message' => 'Datos del carrito incorrectos.']);
        exit;
    }
    $totalVenta += $producto['subtotal'];
}

// Iniciar transacción
$conn->begin_transaction();

try {
    // Comprobar que hay suficiente stock para cada producto
    foreach ($carrito as $producto) {
        $stmtStockCheck = $conn->prepare("SELECT cantidad_en_stock FROM productos WHERE id_producto = ?");
        $stmtStockCheck->bind_param("i", $producto['id']);
        $stmtStockCheck->execute();
        $stmtStockCheck->bind_result($stockDisponible);
        $stmtStockCheck->fetch();
        $stmtStockCheck->close();

        if ($producto['cantidad'] > $stockDisponible) {
            // Si el stock es insuficiente, hacer rollback y enviar mensaje de error
            echo json_encode(['success' => false, 'message' => 'Stock insuficiente para el producto: ' . $producto['nombre']]);
            $conn->rollback();
            exit;
        }
    }

    // Insertar la venta en la tabla `Ventas`
    $stmt = $conn->prepare("INSERT INTO ventas (id_usuario, total) VALUES (?, ?)");
    $stmt->bind_param("id", $id_usuario, $totalVenta);  // Usamos el id_usuario desde la sesión
    if ($stmt->execute()) {
        $id_venta = $stmt->insert_id; // Obtener el ID de la venta recién insertada

        // Insertar los detalles de la venta en la tabla `DetalleVentas`
        foreach ($carrito as $producto) {
            $stmtDetalle = $conn->prepare("INSERT INTO detalleventas (id_venta, id_producto, cantidad, precio, subtotal) VALUES (?, ?, ?, ?, ?)");
            $stmtDetalle->bind_param("iiidd", $id_venta, $producto['id'], $producto['cantidad'], $producto['precio'], $producto['subtotal']);
            $stmtDetalle->execute();

            // Reducir la cantidad en stock del producto
            $stmtStock = $conn->prepare("UPDATE productos SET cantidad_en_stock = cantidad_en_stock - ? WHERE id_producto = ?");
            $stmtStock->bind_param("ii", $producto['cantidad'], $producto['id']);
            $stmtStock->execute();
        }

        // Confirmar la transacción
        $conn->commit();

        // Responder con éxito
        echo json_encode(['success' => true, 'message' => 'Venta procesada correctamente.', 'total' => $totalVenta]);
    } else {
        // Si falla la inserción de la venta, hacer rollback
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al procesar la venta.']);
    }

    // Cerrar la preparación de los statements
    $stmt->close();
    $stmtDetalle->close();
    $stmtStock->close();
} catch (Exception $e) {
    // Si ocurre una excepción, hacer rollback
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al procesar la venta: ' . $e->getMessage()]);
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
