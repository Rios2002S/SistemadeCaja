<?php
require_once 'cn.php'; // Archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];

    // Eliminar el producto de la base de datos
    $stmt = $conn->prepare("DELETE FROM Productos WHERE id_producto = ?");
    $stmt->bind_param("i", $id_producto);
    
    if ($stmt->execute()) {
        header("Location: ../panel_administrador/inventario.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
