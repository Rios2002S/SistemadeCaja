<?php
require_once 'cn.php'; // Archivo de conexión

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad_en_stock'];
    $id_categoria = $_POST['id_categoria'] ?: null;

    // Manejar la imagen si se subió
    $imagen = null;
    if (!empty($_FILES['imagen']['name'])) {
        $imagen = '../uploads/' . basename($_FILES['imagen']['name']);
        move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen);
    }

    // Insertar el producto en la base de datos
    $stmt = $conn->prepare("INSERT INTO productos (nombre, descripcion, precio, cantidad_en_stock, imagen, id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdiss", $nombre, $descripcion, $precio, $cantidad, $imagen, $id_categoria);
    
    if ($stmt->execute()) {
        header("Location: ../panel_administrador/inventario.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
