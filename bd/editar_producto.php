<?php
require_once 'cn.php'; // Archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_producto = $_POST['id_producto'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad_en_stock'];

    // Manejar la imagen si se subió
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = '../uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    // Actualizar los datos del producto
    if ($imagen) {
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad_en_stock = ?, imagen = ? WHERE id_producto = ?");
        $stmt->bind_param("ssdssi", $nombre, $descripcion, $precio, $cantidad, $imagen, $id_producto);
    } else {
        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, cantidad_en_stock = ? WHERE id_producto = ?");
        $stmt->bind_param("ssdsi", $nombre, $descripcion, $precio, $cantidad, $id_producto);
    }
    
    if ($stmt->execute()) {
        header("Location: ../panel_administrador/inventario.php#producto$id_producto");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
