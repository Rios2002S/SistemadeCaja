<?php
// add_sucursal.php

require_once 'cn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_sucursal = $_POST['nombre_sucursal'];
    $direccion = $_POST['direccion'];
    $num_tel = $_POST['num_tel'];

    // Insertar en la base de datos
    $sql = "INSERT INTO sucursales (nombre_sucursal, direccion, num_tel) VALUES ('$nombre_sucursal', '$direccion', '$num_tel')";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../panel_administrador/configuracion.php'); // Redirigir después de agregar
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
