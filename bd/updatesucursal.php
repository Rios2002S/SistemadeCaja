<?php
// updatesucursal.php

require_once 'cn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los valores del formulario
    $id_sucursal = $_POST['id_sucursal'];
    $nombre_sucursal = $_POST['nombre_sucursal'];
    $direccion = $_POST['direccion'];
    $num_tel = $_POST['num_tel'];

    // Actualizar la sucursal en la base de datos
    $sql = "UPDATE sucursales SET 
                nombre_sucursal = '$nombre_sucursal', 
                direccion = '$direccion', 
                num_tel = '$num_tel' 
            WHERE id_sucursal = '$id_sucursal'";

    if ($conn->query($sql) === TRUE) {
        header('Location: ../panel_administrador/configuracion.php'); // Redirigir después de actualizar
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
